<?php

namespace App\Services;

use App\Mail\SupportTicketMail;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Models\UserNotification;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Mail;

class SupportTicketNotificationService
{
    public function notifyCreated(SupportTicket $ticket): void
    {
        $ticket->load('user');

        UserNotification::query()->create([
            'user_id' => $ticket->user_id,
            'title' => 'Support Ticket Created',
            'body' => "Ticket {$ticket->reference}: {$ticket->subject}",
        ]);

        if ($ticket->user?->email) {
            Mail::to($ticket->user->email)->send(new SupportTicketMail($ticket, 'created'));
        }

        $adminEmail = WebsiteSetting::cached()->company_email;
        if ($adminEmail) {
            Mail::to($adminEmail)->send(new SupportTicketMail($ticket, 'admin_new'));
        }
    }

    public function notifyReply(SupportTicket $ticket, SupportTicketReply $reply): void
    {
        $ticket->load('user');

        if ($reply->is_staff) {
            UserNotification::query()->create([
                'user_id' => $ticket->user_id,
                'title' => 'Support Ticket Reply',
                'body' => "New reply on ticket {$ticket->reference}.",
            ]);

            if ($ticket->user?->email) {
                Mail::to($ticket->user->email)->send(new SupportTicketMail($ticket, 'reply', $reply));
            }
        } else {
            $adminEmail = WebsiteSetting::cached()->company_email;
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new SupportTicketMail($ticket, 'admin_reply', $reply));
            }
        }
    }
}
