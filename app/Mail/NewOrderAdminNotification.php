<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderAdminNotification extends Mailable
{
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->load(['user', 'items', 'slot']);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "🚨 New Order Placed #{$this->order->order_number} - AquaFlow Delivery",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_new_order',
        );
    }
}
