<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerWelcomeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $companyName;
    public string $loginUrl;
    public string $supportEmail;
    public string $supportPhone;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $this->companyName = $settings['business_name'] ?? config('app.name', 'AquaFlow');
        $this->supportEmail = $settings['business_email'] ?? env('MAIL_FROM_ADDRESS', 'support@aquaflowdelivery.test');
        $this->supportPhone = $settings['business_phone'] ?? '+91 98765 43210';
        $this->loginUrl = route('login');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Welcome to {$this->companyName} – Your Account Is Ready!",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.customer_welcome',
        );
    }
}
