<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = config('app.frontend_url', 'http://localhost:3000').'/reset-password?'.http_build_query([
            'token' => $this->token,
            'email' => $notifiable->email,
        ]);

        $logoUrl = config('app.frontend_url', 'http://localhost:3000').'/img/logo/logo-light-full.png';

        return (new MailMessage)
            ->subject('Reset Password')
            ->view('emails.reset-password', [
                'frontendUrl' => $resetUrl,
                'logoUrl' => $logoUrl,
            ]);
    }
}
