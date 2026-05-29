<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    private const FALLBACK_MAP_EMBED = 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.9663095343008!2d-74.00425878428698!3d40.74076684379132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c259bf5c1654f3%3A0xc80f9cfce5383d5d!2sGoogle!5e0!3m2!1sen!2sin!4v1586000412513!5m2!1sen!2sin';

    public function index(): View
    {
        // $contactDetail = ContactDetail::settings();

        return view('pages.publicView.contact', [
            // 'contactDetail' => $contactDetail,
            // 'contactEmails' => ContactItem::query()->active()->emails()->ordered()->get(),
            // 'contactPhones' => ContactItem::query()->active()->phones()->ordered()->get(),
            // 'socialLinks' => SocialLink::query()->active()->ordered()->get(),
            // 'mapEmbedSrc' => trim((string) $contactDetail->map_link) !== ''
            //     ? $contactDetail->map_link
            //     : self::FALLBACK_MAP_EMBED,
        ]);
    }

    // public function store(StoreContactMessageRequest $request): RedirectResponse
    // {
    //     ContactMessage::query()->create($request->validated());

    //     return redirect()
    //         ->route('contact')
    //         ->with('success', 'Thank you — your message has been sent. We will get back to you soon.');
    // }
}
