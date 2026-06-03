<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupportTicketReplyRequest;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Services\ActivityLogService;
use App\Services\SupportTicketNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketAdminController extends Controller
{
    public function __construct(
        protected SupportTicketNotificationService $notifications,
        protected ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $tickets = SupportTicket::query()
            ->with('user')
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->input('q'), function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('reference', 'like', "%{$term}%")
                        ->orWhere('subject', 'like', "%{$term}%")
                        ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%")->orWhere('email', 'like', "%{$term}%"));
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.support-tickets.index', [
            'tickets' => $tickets,
            'statuses' => SupportTicket::STATUSES,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
        ]);
    }

    public function show(SupportTicket $supportTicket): View
    {
        $supportTicket->load(['user', 'replies.user']);

        return view('admin.support-tickets.show', [
            'ticket' => $supportTicket,
            'statuses' => SupportTicket::STATUSES,
        ]);
    }

    public function reply(StoreSupportTicketReplyRequest $request, SupportTicket $supportTicket): RedirectResponse
    {
        if ($supportTicket->isClosed()) {
            return back()->withErrors(['message' => 'This ticket is closed.']);
        }

        $reply = SupportTicketReply::query()->create([
            'support_ticket_id' => $supportTicket->id,
            'user_id' => $request->user()->id,
            'is_staff' => true,
            'message' => $request->input('message'),
        ]);

        $supportTicket->update(['status' => 'awaiting_customer']);

        $this->notifications->notifyReply($supportTicket, $reply);
        $this->activityLog->log('support_ticket.reply', $supportTicket);

        return back()->with('success', 'Reply sent.');
    }

    public function update(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', SupportTicket::STATUSES)],
            'priority' => ['nullable', 'in:'.implode(',', SupportTicket::PRIORITIES)],
        ]);

        if ($data['status'] === 'closed' && ! $supportTicket->closed_at) {
            $data['closed_at'] = now();
        } elseif ($data['status'] !== 'closed') {
            $data['closed_at'] = null;
        }

        $supportTicket->update($data);
        $this->activityLog->log('support_ticket.updated', $supportTicket, $data);

        return back()->with('success', 'Ticket updated.');
    }
}
