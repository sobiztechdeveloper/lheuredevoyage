<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Services\ActivityLogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactAdminController extends Controller
{
    public function __construct(
        protected ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $contacts = Contact::query()
            ->when($request->input('unread'), fn ($q) => $q->whereNull('read_at'))
            ->when($request->input('q'), function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('subject', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.contacts.index', [
            'contacts' => $contacts,
            'search' => $request->input('q'),
            'unreadOnly' => $request->boolean('unread'),
        ]);
    }

    public function show(Contact $contact): View
    {
        if (! $contact->read_at) {
            $contact->update(['read_at' => now()]);
        }

        return view('admin.contacts.show', compact('contact'));
    }

    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();
        $this->activityLog->log('inquiry.deleted', $contact);

        return redirect()->route('admin.inquiries.index')->with('success', 'Message deleted.');
    }
}
