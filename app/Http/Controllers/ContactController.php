<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use App\Models\ContactDetail;
use App\Models\Faq;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function index(): View
    {
        return view('pages.publicView.contact', [
            'contactDetail' => ContactDetail::query()->first(),
            'faqs' => Faq::query()->active()->ordered()->take(5)->get(),
        ]);
    }

    public function store(StoreContactRequest $request): RedirectResponse
    {
        Contact::query()->create($request->validated());

        return redirect()
            ->route('contact')
            ->with('success', 'Thank you — your message has been sent. We will get back to you soon.');
    }
}
