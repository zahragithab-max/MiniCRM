<?php

namespace App\Mail;

use App\Models\VerificationCode;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public VerificationCode $verificationCode,
        public string $plainCode
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'کد تأیید MiniCRM',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.verification-code',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}