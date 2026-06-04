<?php

namespace App\Mail;

use App\Models\CarBookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CarBookingRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public CarBookingRequest $request,
        public string $type,
        public ?string $previousStatus = null,
        public ?string $documentType = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'received' => "Car request received — {$this->request->reference_number}",
            'status_changed' => "Car request {$this->request->reference_number} — status updated",
            'document_uploaded' => "Documents for {$this->request->reference_number}",
            'admin_new' => "[Admin] New car request {$this->request->reference_number}",
            default => "Car booking {$this->request->reference_number}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.car-booking-request',
        );
    }
}
