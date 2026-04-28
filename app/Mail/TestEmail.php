<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class TestEmail extends Mailable
{
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Test Email Resend 🚀',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.test'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}