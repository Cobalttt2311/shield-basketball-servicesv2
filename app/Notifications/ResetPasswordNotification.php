<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;

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
        $resetUrl = url('/reset-password?' . http_build_query([
            'token' => $this->token,
            'email' => $notifiable->email,
        ]));

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Reset Password')
            ->view('emails.reset-password', [
                'frontendUrl' => $resetUrl
            ]);
    }
}