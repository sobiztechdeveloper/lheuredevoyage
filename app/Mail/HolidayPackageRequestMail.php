<?php

namespace App\Mail;

use App\Models\HolidayPackageRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HolidayPackageRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public HolidayPackageRequest $request,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "[Holiday Request] {$this->request->reference_number} — {$this->request->destination}",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.holiday-package-request',
        );
    }
}
