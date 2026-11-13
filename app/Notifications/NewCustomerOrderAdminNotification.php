<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCustomerOrderAdminNotification extends Notification
{
    use Queueable;

    public Order $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
        $orderDate = $this->order->created_at ? $this->order->created_at->format('d M Y, h:i A') : now()->format('d M Y, h:i A');

        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'customer_name' => $customerName,
            'customer_phone' => $this->order->user->phone ?? '',
            'order_amount' => (float) $this->order->total_amount,
            'order_date_time' => $orderDate,
            'order_status' => $this->order->order_status,
            'payment_status' => $this->order->payment_status,
            'payment_method' => $this->order->payment_method,
            'target_url' => route('admin.orders.detail', $this->order->id),
            'title' => 'New Customer Order Received',
            'message' => "Order #{$this->order->order_number} placed by {$customerName} for ₹" . number_format($this->order->total_amount, 2),
        ];
    }
}
