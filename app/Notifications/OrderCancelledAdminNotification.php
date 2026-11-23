<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class OrderCancelledAdminNotification extends Notification
{
    use Queueable;

    public Order $order;
    public string $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $reason)
    {
        $this->order = $order;
        $this->reason = $reason;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $customerName = $this->order->user->name ?? 'Customer';

        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $customerName,
            'customer_phone' => $this->order->user->phone ?? '',
            'order_amount' => (float) $this->order->total_amount,
            'cancellation_reason' => $this->reason,
            'target_url' => route('admin.orders.detail', $this->order->id),
            'title' => 'Order Cancelled by Customer',
            'message' => "Order #{$this->order->order_number} was cancelled by {$customerName}. Reason: {$this->reason}",
        ];
    }
}
