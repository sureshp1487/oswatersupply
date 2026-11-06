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
            'failure_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $assignment->status = $validated['status'];

        if ($validated['status'] === 'delivered') {
            $assignment->completed_at = now();
            $order->order_status = 'delivered';
            $order->delivered_at = now();

            // Record doorstep cash collection if any
            $cash = (float)($validated['collected_cash'] ?? 0);
            if ($cash > 0) {
                $assignment->collected_cash = $cash;
                $paymentService->recordPayment(
                    user: $order->user,
                    amount: $cash,
                    paymentMethod: 'cash_offline',
                    order: $order,
                    recordedBy: $driver,
                    notes: "Cash collected upon doorstep delivery by Captain {$driver->name}"
                );
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

        return back()->with('success', 'Delivery status updated.');
    }
}
