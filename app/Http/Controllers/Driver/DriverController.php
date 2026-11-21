<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\DeliveryAssignment;
use App\Models\OrderStatusHistory;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DriverController extends Controller
{
    public function dashboard()
    {
        $driver = Auth::user();
        $today = Carbon::today()->toDateString();

        $assignments = DeliveryAssignment::where('driver_id', $driver->id)
            ->whereHas('order')
            ->with(['order.items', 'order.user', 'order.slot'])
            ->latest()
            ->get();

        $completedToday = DeliveryAssignment::where('driver_id', $driver->id)
            ->where('status', 'delivered')
            ->whereDate('completed_at', $today)
            ->count();

        $cashCollectedToday = DeliveryAssignment::where('driver_id', $driver->id)
            ->whereDate('completed_at', $today)
            ->sum('collected_cash');

        return view('driver.dashboard', compact('driver', 'assignments', 'completedToday', 'cashCollectedToday'));
    }

    public function updateDeliveryStatus(Request $request, $assignmentId, PaymentService $paymentService)
    {
        $driver = Auth::user();
        $assignment = DeliveryAssignment::where('driver_id', $driver->id)->with('order.user')->findOrFail($assignmentId);
        $order = $assignment->order;

        $validated = $request->validate([
            'status' => ['required', 'in:in_transit,delivered,failed'],
            'collected_cash' => ['nullable', 'numeric', 'min:0'],
            'payment_method' => ['nullable', 'in:cash_offline,razorpay'],
            'razorpay_payment_id' => ['nullable', 'string'],
            'razorpay_order_id' => ['nullable', 'string'],
            'razorpay_signature' => ['nullable', 'string'],
            'failure_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $assignment->status = $validated['status'];

        if ($validated['status'] === 'delivered') {
            $assignment->completed_at = now();
            $order->order_status = 'delivered';
            $order->delivered_at = now();

            $paymentMethod = $validated['payment_method'] ?? 'cash_offline';
            $amount = (float)($validated['collected_cash'] ?? 0);

            if ($amount > 0) {
                if ($paymentMethod === 'razorpay') {
                    // Verify Razorpay signature if provided
                    if (!empty($validated['razorpay_order_id']) && !empty($validated['razorpay_payment_id']) && !empty($validated['razorpay_signature'])) {
                        $verified = $paymentService->verifySignature(
                            $validated['razorpay_order_id'],
                            $validated['razorpay_payment_id'],
                            $validated['razorpay_signature']
                        );
                        if (!$verified) {
                            return back()->with('error', 'Online payment signature verification failed.');
                        }
                    }

                    $assignment->collected_cash = $amount;
                    $paymentService->recordPayment(
                        user: $order->user,
                        amount: $amount,
                        paymentMethod: 'razorpay',
                        order: $order,
                        gatewayPaymentId: $validated['razorpay_payment_id'] ?? null,
                        gatewayOrderId: $validated['razorpay_order_id'] ?? null,
                        signature: $validated['razorpay_signature'] ?? null,
                        recordedBy: $driver,
                        notes: "Payment collected via Razorpay UPI/QR upon doorstep delivery by Captain {$driver->name}"
                    );
                } else {
                    // Cash On Delivery
                    $assignment->collected_cash = $amount;
                    $paymentService->recordPayment(
                        user: $order->user,
                        amount: $amount,
                        paymentMethod: 'cash_offline',
                        order: $order,
                        recordedBy: $driver,
                        notes: "Cash collected upon doorstep delivery by Captain {$driver->name}"
                    );
                }
            }
        } elseif ($validated['status'] === 'in_transit') {
            $order->order_status = 'out_for_delivery';
        } elseif ($validated['status'] === 'failed') {
            $assignment->failure_reason = $validated['failure_reason'];
        }

        $assignment->save();
        $order->save();

        OrderStatusHistory::create([
            'order_id' => $order->id,
            'status' => $order->order_status,
            'notes' => "Delivery status updated to {$validated['status']} by Captain {$driver->name}",
            'updated_by' => $driver->id,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => $assignment->status,
                'message' => 'Delivery status updated successfully.'
            ]);
        }

        return back()->with('success', 'Delivery status updated.');
    }

    public function createRazorpayOrder(Request $request, $assignmentId, PaymentService $paymentService)
    {
        $driver = Auth::user();
        $assignment = DeliveryAssignment::where('driver_id', $driver->id)->with('order.user')->findOrFail($assignmentId);
        $order = $assignment->order;

        $amount = (float)($request->input('amount') ?: $order->due_amount);

        if ($amount <= 0) {
            return response()->json(['error' => 'No due amount for this order.'], 422);
        }

        $receiptId = 'DRV_' . $order->order_number . '_' . time();
        $orderData = $paymentService->createRazorpayOrder(
            $amount,
            $receiptId,
            ['order_id' => $order->id, 'driver_id' => $driver->id]
        );

        return response()->json($orderData);
    }
}
