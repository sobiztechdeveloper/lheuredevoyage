<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Quote $quote,
        public string $type,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'sent' => "Your quote {$this->quote->quote_number} from L'Heure De Voyage",
            'accepted' => "Quote {$this->quote->quote_number} accepted — thank you",
            'rejected' => "Quote {$this->quote->quote_number} declined",
            'expired' => "Quote {$this->quote->quote_number} has expired",
            'admin_accepted' => "[Admin] Quote {$this->quote->quote_number} accepted",
            'admin_rejected' => "[Admin] Quote {$this->quote->quote_number} rejected",
            default => "Quote {$this->quote->quote_number}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.quote',
        );
    }
}
