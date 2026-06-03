<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactDetailRequest;
use App\Models\ContactDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactDetailController extends Controller
{
    public function edit(): View
    {
        $contactDetail = ContactDetail::query()->first() ?? new ContactDetail;

        return view('admin.contact-details.edit', compact('contactDetail'));
    }

    public function update(UpdateContactDetailRequest $request): RedirectResponse
    {
        $contactDetail = ContactDetail::query()->first() ?? new ContactDetail;
        $contactDetail->fill($request->validated());
        $contactDetail->save();

        return redirect()->route('admin.contact-details.edit')->with('success', 'Contact details updated.');
    }
}
