<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateContactDetailRequest;
use App\Models\ContactDetail;
use App\Services\CmsImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactDetailController extends Controller
{
    public function edit(): View
    {
        $contactDetail = ContactDetail::query()->first() ?? new ContactDetail;

        return view('admin.contact-details.edit', compact('contactDetail'));
    }

    public function update(UpdateContactDetailRequest $request, CmsImageUploader $uploader): RedirectResponse
    {
        $contactDetail = ContactDetail::query()->first() ?? new ContactDetail;
        $data = $request->safe()->except(['breadcrumb_image']);
        $data['breadcrumb_image'] = $uploader->upload(
            $request->file('breadcrumb_image'),
            'contact',
            $contactDetail->breadcrumb_image
        );

        $contactDetail->fill($data);
        $contactDetail->save();
        ContactDetail::clearCache();

        return redirect()->route('admin.contact-details.edit')->with('success', 'Contact details updated.');
    }
}
