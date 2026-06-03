<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SupportTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public SupportTicket $ticket,
        public string $type,
        public ?SupportTicketReply $reply = null,
    ) {}

    public function envelope(): Envelope
    {
        $subject = match ($this->type) {
            'created' => "Support ticket {$this->ticket->reference} received",
            'reply' => "Reply on ticket {$this->ticket->reference}",
            'admin_new' => "[Admin] New ticket {$this->ticket->reference}",
            'admin_reply' => "[Admin] Customer replied on {$this->ticket->reference}",
            default => "Support ticket {$this->ticket->reference}",
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.support-ticket',
        );
    }
}
