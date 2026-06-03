<?php

namespace App\Mail;

use App\Models\FlightBookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FlightBookingRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public FlightBookingRequest $request,
        public string $type,
        public ?string $previousStatus = null,
        public ?string $documentType = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'received' => "Flight request received — {$this->request->booking_reference}",
            'status_changed' => "Flight request {$this->request->booking_reference} — status updated",
            'document_uploaded' => "Documents for {$this->request->booking_reference}",
            'admin_new' => "[Admin] New flight request {$this->request->booking_reference}",
            default => "Flight booking {$this->request->booking_reference}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.flight-booking-request',
        );
    }
}
