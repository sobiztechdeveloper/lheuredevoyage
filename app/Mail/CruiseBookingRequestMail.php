<?php

namespace App\Mail;

use App\Models\CruiseBookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CruiseBookingRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CruiseBookingRequest $request,
        public string $type,
        public ?string $previousStatus = null,
        public ?string $documentType = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'received' => "Cruise request received — {$this->request->reference_number}",
            'status_changed' => "Cruise request {$this->request->reference_number} — status updated",
            'document_uploaded' => "Documents for {$this->request->reference_number}",
            'admin_new' => "[Admin] New cruise request {$this->request->reference_number}",
            default => "Cruise booking {$this->request->reference_number}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cruise-booking-request',
        );
    }
}
