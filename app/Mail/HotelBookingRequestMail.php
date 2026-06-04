<?php

namespace App\Mail;

use App\Models\HotelBookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class HotelBookingRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public HotelBookingRequest $request,
        public string $type,
        public ?string $previousStatus = null,
        public ?string $documentType = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'received' => "Hotel request received — {$this->request->reference_number}",
            'status_changed' => "Hotel request {$this->request->reference_number} — status updated",
            'document_uploaded' => "Documents for {$this->request->reference_number}",
            'admin_new' => "[Admin] New hotel request {$this->request->reference_number}",
            default => "Hotel booking {$this->request->reference_number}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.hotel-booking-request',
        );
    }
}
