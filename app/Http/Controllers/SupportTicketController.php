<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupportTicketReplyRequest;
use App\Http\Requests\StoreSupportTicketRequest;
use App\Models\SupportTicket;
use App\Models\SupportTicketReply;
use App\Services\SupportTicketNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function __construct(
        protected SupportTicketNotificationService $notifications,
    ) {}

    public function index(): View
    {
        $tickets = Auth::user()
            ->supportTickets()
            ->latest()
            ->paginate(10);

        return view('pages.publicUserView.support-tickets.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('pages.publicUserView.support-tickets.create', [
            'categories' => SupportTicket::CATEGORIES,
            'priorities' => SupportTicket::PRIORITIES,
        ]);
    }

    public function store(StoreSupportTicketRequest $request): RedirectResponse
    {
        $ticket = SupportTicket::query()->create([
            'user_id' => $request->user()->id,
            'reference' => SupportTicket::generateReference(),
            'subject' => $request->input('subject'),
            'category' => $request->input('category'),
            'priority' => $request->input('priority', 'normal'),
            'status' => 'open',
        ]);

        SupportTicketReply::query()->create([
            'support_ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'is_staff' => false,
            'message' => $request->input('message'),
        ]);

        $this->notifications->notifyCreated($ticket);

        return redirect()
            ->route('support-tickets.show', $ticket)
            ->with('success', 'Your support ticket has been submitted.');
    }

    public function show(SupportTicket $supportTicket): View
    {
        $this->authorize('view', $supportTicket);
        $supportTicket->load(['replies.user']);

        return view('pages.publicUserView.support-tickets.show', [
            'ticket' => $supportTicket,
        ]);
    }

    public function reply(StoreSupportTicketReplyRequest $request, SupportTicket $supportTicket): RedirectResponse
    {
        $this->authorize('reply', $supportTicket);

        $reply = SupportTicketReply::query()->create([
            'support_ticket_id' => $supportTicket->id,
            'user_id' => $request->user()->id,
            'is_staff' => false,
            'message' => $request->input('message'),
        ]);

        $supportTicket->update(['status' => 'awaiting_admin']);

        $this->notifications->notifyReply($supportTicket, $reply);

        return back()->with('success', 'Your reply has been sent.');
    }
}
