<?php

namespace App\Mail;

use App\Models\InsuranceBookingRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InsuranceBookingRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public InsuranceBookingRequest $request,
        public string $type,
        public ?string $previousStatus = null,
        public ?string $documentType = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'received' => "Insurance request received — {$this->request->reference_number}",
            'status_changed' => "Insurance request {$this->request->reference_number} — status updated",
            'document_uploaded' => "Documents for {$this->request->reference_number}",
            'admin_new' => "[Admin] New insurance request {$this->request->reference_number}",
            default => "Insurance booking {$this->request->reference_number}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.insurance-booking-request',
        );
    }
}
