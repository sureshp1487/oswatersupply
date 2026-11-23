<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CustomerProfile;
use App\Models\DeliverySlot;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    /**
     * Display subscription plans and active customer subscriptions.
     */
    public function index()
    {
        $user = Auth::user();
        $plans = SubscriptionPlan::where('is_active', true)->get();
        $mySubscriptions = Subscription::where('user_id', $user->id)
            ->with(['plan', 'product', 'deliverySlot', 'address'])
            ->latest()
            ->get();

        $products = Product::where('is_active', true)->where('stock_quantity', '>', 0)->get();
        $addresses = $user->addresses()->get();
        $slots = DeliverySlot::where('is_active', true)->get();
        $profile = CustomerProfile::firstOrCreate(['user_id' => $user->id]);

        return view('customer.subscriptions.index', compact('user', 'plans', 'mySubscriptions', 'products', 'addresses', 'slots', 'profile'));
    }

    /**
     * Store a newly created subscription.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_id' => ['required', 'exists:subscription_plans,id'],
            'product_id' => ['required', 'exists:products,id'],
            'quantity_per_delivery' => ['required', 'integer', 'min:1', 'max:20'],
            'delivery_slot_id' => ['required', 'exists:delivery_slots,id'],
            'address_id' => ['required', 'exists:addresses,id'],
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'preferred_weekday' => ['nullable', 'string', 'in:monday,tuesday,wednesday,thursday,friday,saturday,sunday'],
            'payment_method' => ['required', 'in:cod,razorpay'],
            'special_instructions' => ['nullable', 'string', 'max:500'],
        ], [
            'plan_id.required' => 'Please select a subscription plan.',
            'product_id.required' => 'Please select a water can product.',
            'address_id.required' => 'Please select your delivery address.',
            'delivery_slot_id.required' => 'Please select a preferred delivery slot.',
            'start_date.required' => 'Please choose when your subscription should start.',
        ]);

        $user = Auth::user();
        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);
        $product = Product::findOrFail($validated['product_id']);
        $address = Address::where('user_id', $user->id)->findOrFail($validated['address_id']);
        $slot = DeliverySlot::findOrFail($validated['delivery_slot_id']);

        $startDate = Carbon::parse($validated['start_date']);
        $quantity = (int) $validated['quantity_per_delivery'];
        $deliveriesCount = (int) $plan->deliveries_count;

        // Calculate pricing
        $pricePerCan = (float) $product->selling_price;
        $subtotal = $pricePerCan * $quantity * $deliveriesCount;
        $discountPct = (float) $plan->discount_percentage;
        $discountAmount = round($subtotal * ($discountPct / 100), 2);
        $totalAmount = round($subtotal - $discountAmount, 2);

        // Calculate End Date and First Delivery Date based on frequency
        $nextDeliveryDate = $startDate->copy();

        if ($plan->frequency === 'weekly' && !empty($validated['preferred_weekday'])) {
            $targetWeekday = strtolower($validated['preferred_weekday']);
            // Align to requested weekday on or after start date
            while (strtolower($nextDeliveryDate->format('l')) !== $targetWeekday) {
                $nextDeliveryDate->addDay();
            }
            $endDate = $nextDeliveryDate->copy()->addWeeks($deliveriesCount);
        } elseif ($plan->frequency === 'alternate_days') {
            $endDate = $startDate->copy()->addDays($deliveriesCount * 2);
        } else {
            // daily
            $endDate = $startDate->copy()->addDays($deliveriesCount);
        }

        $subscriptionNumber = 'SUB-' . strtoupper(Str::random(6));

        $subscription = DB::transaction(function () use (
            $user, $plan, $product, $address, $slot, $validated,
            $subscriptionNumber, $quantity, $deliveriesCount, $pricePerCan,
            $subtotal, $discountAmount, $totalAmount, $startDate, $endDate, $nextDeliveryDate
        ) {
            return Subscription::create([
                'subscription_number' => $subscriptionNumber,
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'product_id' => $product->id,
                'quantity_per_delivery' => $quantity,
                'delivery_slot_id' => $slot->id,
                'address_id' => $address->id,
                'address_snapshot' => [
                    'label' => $address->label,
                    'door_number' => $address->door_number,
                    'house_or_building' => $address->house_or_building,
                    'street' => $address->street,
                    'area_locality' => $address->area_locality,
                    'city' => $address->city,
                    'pincode' => $address->pincode,
                    'latitude' => $address->latitude,
                    'longitude' => $address->longitude,
                ],
                'frequency' => $plan->frequency,
                'preferred_weekday' => $validated['preferred_weekday'] ?? null,
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'next_delivery_date' => $nextDeliveryDate->toDateString(),
                'total_deliveries' => $deliveriesCount,
                'completed_deliveries' => 0,
                'price_per_can' => $pricePerCan,
                'subtotal' => $subtotal,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'paid_amount' => 0.00,
                'status' => 'active',
                'payment_method' => $validated['payment_method'],
                'payment_status' => 'pending',
                'special_instructions' => $validated['special_instructions'] ?? null,
            ]);
        });

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Subscription #{$subscription->subscription_number} ({$plan->name}) activated successfully!",
                'subscription_id' => $subscription->id,
                'redirect_url' => route('customer.subscriptions.index'),
            ]);
        }

        return redirect()->route('customer.subscriptions.index')
            ->with('success', "Subscription #{$subscription->subscription_number} ({$plan->name}) created successfully!");
    }

    /**
     * Pause an active subscription.
     */
    public function pause(Request $request, $id)
    {
        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->id)->findOrFail($id);

        if ($subscription->status !== 'active') {
            return back()->with('error', 'Only active subscriptions can be paused.');
        }

        $subscription->status = 'paused';
        $subscription->paused_at = now();
        $subscription->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Subscription #{$subscription->subscription_number} is now paused. Deliveries are temporarily on hold.",
            ]);
        }

        return back()->with('success', "Subscription #{$subscription->subscription_number} has been paused.");
    }

    /**
     * Resume a paused subscription.
     */
    public function resume(Request $request, $id)
    {
        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->id)->findOrFail($id);

        if ($subscription->status !== 'paused') {
            return back()->with('error', 'Only paused subscriptions can be resumed.');
        }

        // Resume with next delivery starting tomorrow (or aligned weekday for weekly plans)
        $resumeDate = Carbon::tomorrow();
        if ($subscription->frequency === 'weekly' && !empty($subscription->preferred_weekday)) {
            $targetDay = strtolower($subscription->preferred_weekday);
            while (strtolower($resumeDate->format('l')) !== $targetDay) {
                $resumeDate->addDay();
            }
        }

        $subscription->status = 'active';
        $subscription->paused_at = null;
        $subscription->next_delivery_date = $resumeDate->toDateString();
        $subscription->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Subscription #{$subscription->subscription_number} is active again! Next scheduled delivery: " . $resumeDate->format('M d, Y'),
            ]);
        }

        return back()->with('success', "Subscription resumed successfully.");
    }

    /**
     * Cancel an active or paused subscription.
     */
    public function cancel(Request $request, $id)
    {
        $user = Auth::user();
        $subscription = Subscription::where('user_id', $user->id)->findOrFail($id);

        if (in_array($subscription->status, ['cancelled', 'completed'])) {
            return back()->with('error', 'This subscription is already inactive.');
        }

        $validated = $request->validate([
            'reason_preset' => ['required', 'string', 'max:255'],
            'reason_details' => ['nullable', 'string', 'max:500'],
        ], [
            'reason_preset.required' => 'Please select a reason for cancelling your subscription.',
        ]);

        $preset = trim($validated['reason_preset']);
        $details = trim($validated['reason_details'] ?? '');
        $fullReason = $preset;
        if (!empty($details) && $preset !== $details) {
            $fullReason .= ' - ' . $details;
        }

        $subscription->status = 'cancelled';
        $subscription->cancellation_reason = $fullReason;
        $subscription->cancelled_at = now();
        $subscription->next_delivery_date = null;
        $subscription->save();

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Subscription #{$subscription->subscription_number} has been cancelled.",
            ]);
        }

        return back()->with('success', "Subscription #{$subscription->subscription_number} has been cancelled.");
    }
}
