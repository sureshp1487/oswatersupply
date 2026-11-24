<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\DeliverySlot;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Address;
use App\Models\CustomerProfile;
use App\Models\CustomerPaymentLedger;
use App\Services\PaymentService;
use App\Mail\NewOrderAdminNotification;
use App\Notifications\NewCustomerOrderAdminNotification;
use App\Notifications\OrderCancelledAdminNotification;
use App\Models\User;
use App\Models\Banner;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function home()
    {
        $user = Auth::user();
        $profile = CustomerProfile::firstOrCreate(
            ['user_id' => $user->id],
            ['total_due_balance' => 0.00, 'onboarding_completed' => true]
        );

        $categories = ProductCategory::where('is_active', true)->with(['products' => function ($q) {
            $q->where('is_active', true)->orderBy('selling_price', 'asc');
        }])->get();

        $featuredProducts = Product::where('is_active', true)->orderBy('volume', 'desc')->get();

        $upcomingDeliveries = Order::where('user_id', $user->id)
            ->whereIn('order_status', ['placed', 'confirmed', 'preparing', 'out_for_delivery'])
            ->with(['items', 'slot'])
            ->orderBy('expected_delivery_date', 'asc')
            ->take(3)
            ->get();

        $recentOrders = Order::where('user_id', $user->id)
            ->with(['items'])
            ->latest()
            ->take(5)
            ->get();

        $defaultAddress = $user->defaultAddress ?: $user->addresses()->first();

        $banners = Banner::where('is_active', true)->orderBy('display_order', 'asc')->get();
        $services = Service::where('is_active', true)->orderBy('display_order', 'asc')->get();

        return view('customer.home', compact('user', 'profile', 'categories', 'featuredProducts', 'upcomingDeliveries', 'recentOrders', 'defaultAddress', 'banners', 'services'));
    }

    public function services()
    {
        $services = Service::where('is_active', true)->orderBy('display_order', 'asc')->get();
        return view('customer.services', compact('services'));
    }

    public function products()
    {
        $products = Product::where('is_active', true)->orderBy('selling_price', 'asc')->get();
        $categories = ProductCategory::where('is_active', true)->get();
        return view('customer.products', compact('products', 'categories'));
    }

    public function bookWater()
    {
        $user = Auth::user();
        $products = Product::where('is_active', true)->where('stock_quantity', '>', 0)->get();
        $addresses = $user->addresses()->get();
        $slots = DeliverySlot::where('is_active', true)->get();
        $profile = $user->customerProfile;

        return view('customer.book', compact('user', 'products', 'addresses', 'slots', 'profile'));
    }

    public function placeOrder(Request $request, PaymentService $paymentService)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'address_id' => ['required', 'exists:addresses,id'],
            'expected_delivery_date' => ['required', 'date', 'after_or_equal:today'],
            'delivery_slot_id' => ['required', 'exists:delivery_slots,id'],
            'payment_method' => ['required', 'in:razorpay,cod'],
            'delivery_instructions' => ['nullable', 'string', 'max:500'],
            // Razorpay verify attributes if payment_method is razorpay
            'razorpay_payment_id' => ['nullable', 'string'],
            'razorpay_order_id' => ['nullable', 'string'],
            'razorpay_signature' => ['nullable', 'string'],
        ]);

        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->findOrFail($validated['address_id']);
        $slot = DeliverySlot::findOrFail($validated['delivery_slot_id']);

        // Check if selected time slot has passed for today
        if ($slot->isPassedForDate($validated['expected_delivery_date'])) {
            return response()->json([
                'success' => false,
                'message' => "The selected time slot ({$slot->name}) has already closed for today. Please select an upcoming slot or choose a future delivery date."
            ], 422);
        }

        // Check slot capacity for expected delivery date
        $currentBookingsCount = Order::where('expected_delivery_date', $validated['expected_delivery_date'])
            ->where('delivery_slot_id', $slot->id)
            ->where('order_status', '!=', 'cancelled')
            ->count();

        if ($currentBookingsCount >= $slot->max_daily_capacity) {
            return response()->json([
                'success' => false,
                'message' => "The selected time slot has reached its maximum daily delivery capacity ({$slot->max_daily_capacity} orders). Please select an alternate slot or date.",
            ], 422);
        }

        // Calculate Totals & snapshot items
        $subtotal = 0;
        $orderItemsData = [];

        foreach ($validated['items'] as $itemInput) {
            $product = Product::findOrFail($itemInput['product_id']);

            if ($product->stock_quantity < $itemInput['quantity']) {
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient stock for {$product->name}. Only {$product->stock_quantity} available.",
                ], 422);
            }

            $itemTotal = round($product->selling_price * $itemInput['quantity'], 2);
            $subtotal += $itemTotal;

            $orderItemsData[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'volume_snapshot' => "{$product->volume} {$product->unit}",
                'unit_price' => $product->selling_price,
                'quantity' => $itemInput['quantity'],
                'total_price' => $itemTotal,
            ];
        }

        $deliveryFee = 0.00;
        $taxAmount = 0.00;
        $totalAmount = $subtotal + $deliveryFee + $taxAmount;

        $paymentMethod = $validated['payment_method'];
        $initialPaymentStatus = 'pending';
        $paidAmount = 0.00;
        $dueAmount = $totalAmount;

        // If Razorpay, verify payment signature
        if ($paymentMethod === 'razorpay') {
            $verified = $paymentService->verifySignature(
                $validated['razorpay_order_id'] ?? '',
                $validated['razorpay_payment_id'] ?? '',
                $validated['razorpay_signature'] ?? ''
            );

            if (! $verified) {
                return response()->json([
                    'success' => false,
                    'message' => 'Online payment verification failed. Please try again.',
                ], 400);
            }

            $initialPaymentStatus = 'paid';
            $paidAmount = $totalAmount;
            $dueAmount = 0.00;
        }

        // Database transaction for atomic consistency
        $order = DB::transaction(function () use (
            $user, $address, $slot, $validated, $subtotal, $deliveryFee,
            $taxAmount, $totalAmount, $paidAmount, $dueAmount, $paymentMethod,
            $initialPaymentStatus, $orderItemsData, $paymentService
        ) {
            $order = Order::create([
                'order_number' => 'AQF-' . strtoupper(bin2hex(random_bytes(3))),
                'user_id' => $user->id,
                'address_id' => $address->id,
                'address_snapshot' => [
                    'label' => $address->label,
                    'house_or_building' => $address->house_or_building,
                    'door_number' => $address->door_number,
                    'street' => $address->street,
                    'area_locality' => $address->area_locality,
                    'city' => $address->city,
                    'pincode' => $address->pincode,
                    'delivery_instructions' => $address->delivery_instructions,
                    'latitude' => $address->latitude,
                    'longitude' => $address->longitude,
                ],
                'expected_delivery_date' => $validated['expected_delivery_date'],
                'delivery_slot_id' => $slot->id,
                'order_status' => 'placed',
                'payment_method' => $paymentMethod,
                'payment_status' => $initialPaymentStatus,
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'tax_amount' => $taxAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'due_amount' => $dueAmount,
                'delivery_instructions' => $validated['delivery_instructions'] ?? null,
            ]);

            // Save order items & decrement inventory
            foreach ($orderItemsData as $itemData) {
                $itemData['order_id'] = $order->id;
                OrderItem::create($itemData);

                // Reduce inventory stock
                Product::where('id', $itemData['product_id'])->decrement('stock_quantity', $itemData['quantity']);
            }

            // Create initial order history
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'placed',
                'notes' => 'Order successfully placed by customer.',
                'updated_by' => $user->id,
            ]);

            // Profile accounting
            $profile = CustomerProfile::firstOrCreate(['user_id' => $user->id]);

            if ($paymentMethod === 'pay_later') {
                // Add to customer's outstanding balance
                $profile->total_due_balance += $totalAmount;
                $profile->save();

                CustomerPaymentLedger::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'entry_type' => 'debit',
                    'amount' => $totalAmount,
                    'balance_after' => $profile->total_due_balance,
                    'description' => "Order #{$order->order_number} placed on Pay Later / Outstanding Due",
                ]);
            } elseif ($paymentMethod === 'razorpay') {
                $paymentService->recordPayment(
                    user: $user,
                    amount: $totalAmount,
                    paymentMethod: 'razorpay',
                    order: $order,
                    gatewayPaymentId: $validated['razorpay_payment_id'] ?? null,
                    gatewayOrderId: $validated['razorpay_order_id'] ?? null,
                    signature: $validated['razorpay_signature'] ?? null,
                    notes: "Online Payment for Order #{$order->order_number}"
                );
            }

            return $order;
        });

        // 1. Immediately send In-App & PWA Real-Time Notification to all Admin Users (Instant database write)
        try {
            $adminUsers = User::where('role', 'admin')->get();
            foreach ($adminUsers as $admin) {
                // Prevent duplicate notification for same order if already exists
                $hasExisting = $admin->notifications()
                    ->where('data', 'like', '%"order_id":' . $order->id . '%')
                    ->exists();

                if (!$hasExisting) {
                    $admin->notify(new NewCustomerOrderAdminNotification($order));
                }
            }
        } catch (\Throwable $e) {
            Log::error("Failed sending database order notification to admin users: " . $e->getMessage());
        }

        // 2. Send Email notification asynchronously or non-blocking to prevent UI delay
        try {
            $adminEmail = env('MAIL_ADMIN') ?: (config('mail.admin_notification_email') ?? env('ADMIN_NOTIFICATION_EMAIL', 'admin@aquaflowdelivery.test'));
            $bccEmail = env('MAIL_BCC');

            $mailable = new NewOrderAdminNotification($order);
            if (!empty($bccEmail)) {
                $mailable->bcc($bccEmail);
            }

            if (config('queue.default') !== 'sync') {
                Mail::to($adminEmail)->queue($mailable);
            } else {
                Mail::to($adminEmail)->send($mailable);
            }
        } catch (\Throwable $e) {
            Log::error("Failed sending admin order alert: " . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Your water delivery order has been booked successfully!',
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'redirect_url' => route('customer.order.show', $order->id),
        ]);
    }

    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
            ->with(['items', 'slot'])
            ->latest()
            ->paginate(10);

        return view('customer.orders', compact('orders'));
    }

    public function showOrder($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->with(['items', 'slot', 'histories.user', 'transactions'])
            ->findOrFail($id);

        return view('customer.order_detail', compact('order'));
    }

    public function cancelOrder(Request $request, $id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
            ->with(['items', 'deliveryAssignment'])
            ->findOrFail($id);

        if (!$order->canBeCancelled()) {
            $errorMsg = match ($order->order_status) {
                'preparing' => 'This order is currently being prepared & loaded for delivery and cannot be cancelled automatically. Please contact support.',
                'out_for_delivery' => 'The delivery captain is already on the way with your order. It cannot be cancelled.',
                'delivered' => 'This order has already been delivered.',
                'cancelled' => 'This order has already been cancelled.',
                default => 'This order cannot be cancelled at this stage.',
            };

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $errorMsg], 422);
            }
            return back()->with('error', $errorMsg);
        }

        $validated = $request->validate([
            'reason_preset' => ['required', 'string', 'max:255'],
            'reason_details' => ['nullable', 'string', 'max:500'],
        ], [
            'reason_preset.required' => 'Please select a reason for cancellation.',
        ]);

        $preset = trim($validated['reason_preset']);
        $details = trim($validated['reason_details'] ?? '');
        $fullReason = $preset;
        if (!empty($details) && $preset !== $details) {
            $fullReason .= ' - ' . $details;
        }

        DB::transaction(function () use ($user, $order, $fullReason) {
            // 1. Restock inventory
            foreach ($order->items as $item) {
                Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
            }

            // 2. Adjust Pay Later outstanding due if applicable
            $profile = CustomerProfile::firstOrCreate(['user_id' => $user->id]);
            if ($order->due_amount > 0) {
                $reversedDue = min($order->due_amount, $profile->total_due_balance);
                $profile->total_due_balance = max(0, $profile->total_due_balance - $reversedDue);
                $profile->save();

                CustomerPaymentLedger::create([
                    'user_id' => $user->id,
                    'order_id' => $order->id,
                    'entry_type' => 'credit',
                    'amount' => $reversedDue,
                    'balance_after' => $profile->total_due_balance,
                    'description' => "Order #{$order->order_number} cancelled by customer - outstanding balance reversed",
                ]);

                $order->due_amount = 0.00;
            }

            // 3. Cancel delivery assignment if assigned
            if ($order->deliveryAssignment) {
                $order->deliveryAssignment->status = 'cancelled';
                $order->deliveryAssignment->save();
            }

            // 4. Update order status and cancellation reason
            $order->order_status = 'cancelled';
            $order->cancellation_reason = $fullReason;
            $order->cancelled_at = now();
            $order->save();

            // 5. Create OrderStatusHistory record
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'status' => 'cancelled',
                'notes' => 'Self-service cancellation by customer. Reason: ' . $fullReason,
                'updated_by' => $user->id,
            ]);
        });

        // 6. Notify Admin
        try {
            $adminUsers = User::where('role', 'admin')->get();
            foreach ($adminUsers as $admin) {
                $admin->notify(new OrderCancelledAdminNotification($order, $fullReason));
            }
        } catch (\Throwable $e) {
            Log::error("Failed sending order cancellation notification to admins: " . $e->getMessage());
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Order #{$order->order_number} has been cancelled successfully.",
                'redirect_url' => route('customer.order.show', $order->id),
            ]);
        }

        return redirect()->route('customer.order.show', $order->id)
            ->with('success', "Order #{$order->order_number} has been cancelled.");
    }

    public function payments(PaymentService $paymentService)
    {
        $user = Auth::user();
        $profile = CustomerProfile::firstOrCreate(['user_id' => $user->id]);

        $unpaidOrders = Order::where('user_id', $user->id)
            ->whereIn('payment_status', ['pending', 'partially_paid'])
            ->with(['items'])
            ->orderBy('id', 'asc')
            ->get();

        $ledgerEntries = CustomerPaymentLedger::where('user_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        $transactions = $user->paymentTransactions()->latest()->take(15)->get();

        return view('customer.payments', compact('user', 'profile', 'unpaidOrders', 'ledgerEntries', 'transactions'));
    }

    public function payOrderDue(Request $request, $orderId, PaymentService $paymentService)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)->findOrFail($orderId);

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', "max:{$order->due_amount}"],
            'razorpay_payment_id' => ['nullable', 'string'],
            'razorpay_order_id' => ['nullable', 'string'],
            'razorpay_signature' => ['nullable', 'string'],
        ]);

        $verified = $paymentService->verifySignature(
            $validated['razorpay_order_id'] ?? '',
            $validated['razorpay_payment_id'] ?? '',
            $validated['razorpay_signature'] ?? ''
        );

        if (! $verified) {
            return response()->json(['success' => false, 'message' => 'Online payment signature mismatch.'], 400);
        }

        $paymentService->recordPayment(
            user: $user,
            amount: (float)$validated['amount'],
            paymentMethod: 'razorpay',
            order: $order,
            gatewayPaymentId: $validated['razorpay_payment_id'] ?? null,
            gatewayOrderId: $validated['razorpay_order_id'] ?? null,
            signature: $validated['razorpay_signature'] ?? null,
            notes: "Outstanding Due Payment for Order #{$order->order_number}"
        );

        return response()->json([
            'success' => true,
            'message' => "Payment of ₹{$validated['amount']} recorded successfully!",
        ]);
    }

    public function payAllDues(Request $request, PaymentService $paymentService)
    {
        $user = Auth::user();
        $profile = CustomerProfile::firstOrCreate(['user_id' => $user->id]);

        if ($profile->total_due_balance <= 0) {
            return response()->json(['success' => false, 'message' => 'No outstanding dues found.'], 400);
        }

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', "max:{$profile->total_due_balance}"],
            'razorpay_payment_id' => ['nullable', 'string'],
            'razorpay_order_id' => ['nullable', 'string'],
            'razorpay_signature' => ['nullable', 'string'],
        ]);

        $paymentService->settleCustomerOutstandingDues(
            user: $user,
            paymentAmount: (float)$validated['amount'],
            method: 'razorpay',
            gatewayPaymentId: $validated['razorpay_payment_id'] ?? null
        );

        return response()->json([
            'success' => true,
            'message' => "Outstanding dues of ₹{$validated['amount']} cleared successfully!",
        ]);
    }

    public function createRazorpayOrder(Request $request, PaymentService $paymentService)
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1'],
            'receipt' => ['nullable', 'string'],
        ]);

        $orderData = $paymentService->createRazorpayOrder(
            (float)$validated['amount'],
            $validated['receipt'] ?? ('RCPT_' . time())
        );

        return response()->json($orderData);
    }

    public function profile()
    {
        $user = Auth::user()->load(['customerProfile', 'addresses']);
        $slots = DeliverySlot::where('is_active', true)->get();
        return view('customer.profile', compact('user', 'slots'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email:rfc,filter', 'max:255', "unique:users,email,{$user->id}"],
            'preferred_slot_id' => ['nullable', 'exists:delivery_slots,id'],
            'new_password' => ['nullable', 'string', 'min:6'],
        ], [
            'name.required' => 'Full name is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already in use by another account.',
            'new_password.min' => 'Password must be at least 6 characters.',
        ]);

        $user->name = trim($validated['name']);
        if (isset($validated['email'])) {
            $user->email = !empty($validated['email']) ? strtolower(trim($validated['email'])) : null;
        }
        if (!empty($validated['new_password'])) {
            $user->password = $validated['new_password']; // Handled by hashed cast
        }
        $user->save();

        if (isset($validated['preferred_slot_id'])) {
            $profile = CustomerProfile::firstOrCreate(['user_id' => $user->id]);
            $profile->preferred_delivery_slot_id = $validated['preferred_slot_id'];
            $profile->save();
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function saveAddress(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'label' => ['required', 'string', 'max:50'],
            'house_or_building' => ['required', 'string', 'max:255'],
            'door_number' => ['required', 'string', 'max:100'],
            'street' => ['required', 'string', 'max:255'],
            'area_locality' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'regex:/^\d{6}$/'],
            'delivery_instructions' => ['nullable', 'string', 'max:500'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'is_default' => ['nullable', 'boolean'],
        ]);

        if (!empty($validated['is_default'])) {
            Address::where('user_id', $user->id)->update(['is_default' => false]);
        }

        $address = Address::create(array_merge($validated, [
            'user_id' => $user->id,
            'is_default' => !empty($validated['is_default']) || Address::where('user_id', $user->id)->count() === 0,
        ]));

        if ($request->ajax()) {
            return response()->json(['success' => true, 'address' => $address]);
        }

        return back()->with('success', 'Address added successfully.');
    }

    public function setDefaultAddress($id)
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->findOrFail($id);

        Address::where('user_id', $user->id)->update(['is_default' => false]);
        $address->is_default = true;
        $address->save();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Default address updated to {$address->label}.",
                'address' => $address,
            ]);
        }

        return back()->with('success', "Default address updated to {$address->label}.");
    }

    public function deleteAddress($id)
    {
        $user = Auth::user();
        $address = Address::where('user_id', $user->id)->findOrFail($id);
        $wasDefault = $address->is_default;
        $address->delete();

        // If the deleted address was default, make the next existing address default
        if ($wasDefault) {
            $next = Address::where('user_id', $user->id)->first();
            if ($next) {
                $next->is_default = true;
                $next->save();
            }
        }

        return back()->with('success', 'Address removed.');
    }
}
