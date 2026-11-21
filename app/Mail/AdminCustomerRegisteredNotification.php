<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Address;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminCustomerRegisteredNotification extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public ?Address $address;
    public string $companyName;
    public string $adminCustomerDetailsUrl;
    public string $registrationDate;
    public string $registrationTime;
    public string $supportEmail;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, ?Address $address = null)
    {
        $this->user = $user;
        $this->address = $address ?? $user->addresses()->where('is_default', true)->first() ?? $user->addresses()->first();

        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $this->companyName = $settings['business_name'] ?? config('app.name', 'AquaFlow');
        $this->supportEmail = $settings['business_email'] ?? env('MAIL_FROM_ADDRESS', 'support@aquaflowdelivery.test');
        $this->adminCustomerDetailsUrl = route('admin.customers.detail', $user->id);

        $createdAt = $user->created_at ?? now();
        $this->registrationDate = $createdAt->format('M d, Y');
        $this->registrationTime = $createdAt->format('h:i A');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "New Customer Registration Notification – {$this->user->name}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_registration_notification',
        );
    }
}
