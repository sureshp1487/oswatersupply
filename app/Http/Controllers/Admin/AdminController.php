<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use App\Models\CustomerProfile;
use App\Models\CustomerPaymentLedger;
use App\Models\PaymentTransaction;
use App\Models\DeliverySlot;
use App\Models\DeliveryAssignment;
use App\Models\Setting;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $today = Carbon::today()->toDateString();

        $metrics = [
            'total_customers' => User::where('role', 'customer')->count(),
            'total_orders' => Order::count(),
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'upcoming_deliveries' => Order::where('expected_delivery_date', '>=', $today)
                ->whereIn('order_status', ['placed', 'confirmed', 'preparing'])
                ->count(),
            'completed_deliveries' => Order::where('order_status', 'delivered')->count(),
            'cancelled_orders' => Order::where('order_status', 'cancelled')->count(),
            'total_revenue' => Order::sum('paid_amount'),
            'today_revenue' => PaymentTransaction::whereDate('created_at', $today)->where('status', 'success')->sum('amount'),
            'total_outstanding_dues' => CustomerProfile::sum('total_due_balance'),
            'low_stock_count' => Product::where('stock_quantity', '<=', 15)->count(),
        ];

        // Recent orders
        $recentOrders = Order::with(['user', 'items', 'slot'])
            ->latest()
            ->take(8)
            ->get();

        // 7-day revenue trend data for Chart.js
        $last7Days = collect(range(6, 0))->map(function ($daysBack) {
            $date = Carbon::today()->subDays($daysBack)->toDateString();
            $label = Carbon::today()->subDays($daysBack)->format('D, M d');
            $revenue = PaymentTransaction::whereDate('created_at', $date)->where('status', 'success')->sum('amount');
            $orders = Order::whereDate('created_at', $date)->count();

            return [
                'date' => $label,
                'revenue' => (float)$revenue,
                'orders' => $orders,
            ];
        });

        // Top Selling Products
        $topProducts = OrderItem::select('product_name', DB::raw('SUM(quantity) as total_qty'), DB::raw('SUM(total_price) as total_sales'))
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('metrics', 'recentOrders', 'last7Days', 'topProducts'));
    }

    public function orders(Request $request)
    {
        $query = Order::with(['user', 'items', 'slot', 'deliveryAssignment.driver']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('phone', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        if ($request->filled('delivery_date')) {
            $query->whereDate('expected_delivery_date', $request->delivery_date);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        $drivers = User::where('role', 'driver')->where('status', 'active')->get();

        return view('admin.orders.index', compact('orders', 'drivers'));
    }

    public function orderDetail($id)
    {
        $order = Order::with(['user.addresses', 'items.product', 'slot', 'histories.user', 'transactions.recordedByUser', 'deliveryAssignment.driver'])
            ->findOrFail($id);

        $drivers = User::where('role', 'driver')->where('status', 'active')->get();
        $slots = DeliverySlot::where('is_active', true)->get();

        return view('admin.orders.detail', compact('order', 'drivers', 'slots'));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'order_status' => ['required', 'in:placed,confirmed,preparing,out_for_delivery,delivered,cancelled'],
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $order->order_status = $validated['order_status'];
        if ($validated['order_status'] === 'delivered') {
            $order->delivered_at = now();
        }
        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $validated['order_status'],
            'notes' => $validated['notes'] ?? ("Status updated to " . ucfirst(str_replace('_', ' ', $validated['order_status']))),
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function assignDriver(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'driver_id' => ['required', 'exists:users,id'],
        ]);

        $driver = User::where('role', 'driver')->findOrFail($validated['driver_id']);

        // Update or create assignment record
        $assignment = DeliveryAssignment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'driver_id' => $driver->id,
                'assigned_at' => now(),
                'status' => 'assigned',
            ]
        );

        if (in_array($order->order_status, ['placed', 'confirmed'])) {
            $order->order_status = 'preparing';
            $order->save();
        }

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $order->order_status,
            'notes' => "Delivery assigned to Captain {$driver->name} (+91 {$driver->phone})",
            'updated_by' => auth()->id(),
        ]);

        return back()->with('success', "Delivery successfully assigned to Captain {$driver->name}.");
    }

    public function recordOfflinePayment(Request $request, $id, PaymentService $paymentService)
    {
        $order = Order::findOrFail($id);
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', "max:{$order->due_amount}"],
            'payment_method' => ['required', 'in:cash_offline,upi_manual'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $paymentService->recordPayment(
            user: $order->user,
            amount: (float)$validated['amount'],
            paymentMethod: $validated['payment_method'],
            order: $order,
            recordedBy: auth()->user(),
            notes: $validated['notes'] ?? ("Offline collection of ₹{$validated['amount']} recorded by Admin")
        );

        return back()->with('success', "Offline payment of ₹{$validated['amount']} successfully recorded.");
    }

    // Dues Management Module
    public function dues(Request $request)
    {
        $query = CustomerProfile::with('user')
            ->where('total_due_balance', '>', 0);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%")
                   ->orWhere('phone', 'like', "%{$search}%")
                   ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customersWithDues = $query->orderByDesc('total_due_balance')->paginate(15);
        $totalOutstandingAcrossBusiness = CustomerProfile::sum('total_due_balance');

        return view('admin.dues.index', compact('customersWithDues', 'totalOutstandingAcrossBusiness'));
    }

    public function settleCustomerDue(Request $request, $userId, PaymentService $paymentService)
    {
        $user = User::findOrFail($userId);
        $profile = CustomerProfile::where('user_id', $user->id)->firstOrFail();

        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:1', "max:{$profile->total_due_balance}"],
            'payment_method' => ['required', 'in:cash_offline,upi_manual'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $paymentService->settleCustomerOutstandingDues(
            user: $user,
            paymentAmount: (float)$validated['amount'],
            method: $validated['payment_method'],
            adminRecorder: auth()->user()
        );

        return back()->with('success', "Due payment of ₹{$validated['amount']} successfully recorded and allocated!");
    }

    // Products Management
    public function products()
    {
        $products = Product::with('category')->latest()->get();
        $categories = ProductCategory::all();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'volume' => ['required', 'string', 'max:50'],
            'unit' => ['required', 'string', 'max:50'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'min_order_quantity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'product_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            $validated['image'] = '/uploads/products/' . $filename;
        }

        $validated['slug'] = Str::slug($validated['name']) . '-' . rand(100, 999);
        $validated['is_active'] = $request->has('is_active');
        $validated['is_delivery_eligible'] = $request->has('is_delivery_eligible');

        Product::create($validated);

        return back()->with('success', 'Water Can Product added successfully.');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category_id' => ['nullable', 'exists:product_categories,id'],
            'volume' => ['required', 'string', 'max:50'],
            'unit' => ['required', 'string', 'max:50'],
            'selling_price' => ['required', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'min_order_quantity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,svg', 'max:4096'],
        ]);

        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $filename = 'product_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/products'), $filename);
            $validated['image'] = '/uploads/products/' . $filename;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_delivery_eligible'] = $request->has('is_delivery_eligible');

        $product->update($validated);

        return back()->with('success', 'Product updated successfully.');
    }

    // Customers
    public function customers(Request $request)
    {
        $query = User::where('role', 'customer')->with(['customerProfile', 'addresses']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(15)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function customerDetail($id)
    {
        $customer = User::where('role', 'customer')
            ->with(['customerProfile', 'addresses', 'orders.items', 'paymentLedger'])
            ->findOrFail($id);

        $totalSpent = $customer->orders()->where('payment_status', 'paid')->sum('paid_amount');

        return view('admin.customers.detail', compact('customer', 'totalSpent'));
    }

    public function updateCustomer(Request $request, $id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^[6-9]\d{9}$/', 'unique:users,phone,' . $customer->id],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $customer->id],
            'status' => ['required', 'in:active,blocked'],
        ]);

        $customer->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'email' => $validated['email'],
            'status' => $validated['status'],
        ]);

        return back()->with('success', "Customer {$customer->name} updated successfully.");
    }

    public function deleteCustomer($id)
    {
        $customer = User::where('role', 'customer')->findOrFail($id);

        // Check if customer has outstanding dues
        if ($customer->customerProfile && $customer->customerProfile->total_due_balance > 0) {
            return back()->with('error', "Cannot delete customer because they have an outstanding due balance of ₹{$customer->customerProfile->total_due_balance}.");
        }

        $customerName = $customer->name;
        $customer->delete();

        return redirect()->route('admin.customers.index')->with('success', "Customer {$customerName} has been deleted successfully.");
    }

    // Deliveries Overview
    public function deliveries(Request $request)
    {
        $date = $request->get('date', Carbon::today()->toDateString());
        $orders = Order::whereDate('expected_delivery_date', $date)
            ->with(['user', 'slot', 'items', 'deliveryAssignment.driver'])
            ->orderBy('delivery_slot_id', 'asc')
            ->get();

        $drivers = User::where('role', 'driver')->where('status', 'active')->get();

        return view('admin.deliveries.index', compact('orders', 'drivers', 'date'));
    }

    // Settings
    public function settings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $data = $request->except(['_token']);
        foreach ($data as $key => $val) {
            Setting::set($key, $val ?? '');
        }

        cache()->forget('app_settings_global');

        return back()->with('success', 'System configurations updated.');
    }

    // App & Dashboard Customization
    public function customization()
    {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.customization.index', compact('settings'));
    }

    public function updateCustomization(Request $request)
    {
        $validated = $request->validate([
            'business_name' => ['required', 'string', 'max:100'],
            'business_tagline' => ['nullable', 'string', 'max:255'],
            'dashboard_theme' => ['required', 'string', 'in:indigo,emerald,ocean,slate,rose'],
            'primary_color' => ['required', 'string', 'max:20'],
            'accent_color' => ['required', 'string', 'max:20'],
            'announcement_banner' => ['nullable', 'string', 'max:500'],
            'banner_enabled' => ['nullable', 'in:0,1'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,ico', 'max:1024'],
        ]);

        Setting::set('business_name', $validated['business_name'], 'customization');
        Setting::set('business_tagline', $validated['business_tagline'] ?? '', 'customization');
        Setting::set('dashboard_theme', $validated['dashboard_theme'], 'customization');
        Setting::set('primary_color', $validated['primary_color'], 'customization');
        Setting::set('accent_color', $validated['accent_color'], 'customization');
        Setting::set('announcement_banner', $validated['announcement_banner'] ?? '', 'customization');
        Setting::set('banner_enabled', $request->has('banner_enabled') ? '1' : '0', 'customization');

        // Handle Logo Upload if provided
        if ($request->hasFile('logo')) {
            $logoFile = $request->file('logo');
            $ext = $logoFile->getClientOriginalExtension();
            $targetName = "logo.{$ext}";
            $logoFile->move(public_path('icons'), $targetName);
            Setting::set('custom_logo_url', "/icons/{$targetName}", 'customization');
        }

        // Handle Favicon Upload if provided
        if ($request->hasFile('favicon')) {
            $favFile = $request->file('favicon');
            $favFile->move(public_path(), 'favicon.ico');
        }

        cache()->forget('app_settings_global');

        return back()->with('success', 'Dashboard & Brand Customization successfully saved!');
    }
}
