<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordNotification extends Mailable
{
    use Queueable, SerializesModels;

    public User $user;
    public string $token;
    public string $resetUrl;
    public string $companyName;
    public string $supportEmail;
    public string $supportPhone;
    public int $expireMinutes;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, string $token, string $resetUrl)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = $resetUrl;

        $settings = Setting::all()->pluck('value', 'key')->toArray();
        $this->companyName = $settings['business_name'] ?? config('app.name', 'AquaFlow');
        $this->supportEmail = $settings['business_email'] ?? env('MAIL_FROM_ADDRESS', 'support@aquaflowdelivery.test');
        $this->supportPhone = $settings['business_phone'] ?? '+91 98765 43210';
        $this->expireMinutes = (int) config('auth.passwords.users.expire', 60);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Reset Your Password - {$this->companyName}",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset',
        );
    }
}
