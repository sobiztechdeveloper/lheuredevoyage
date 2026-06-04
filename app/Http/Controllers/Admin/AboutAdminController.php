<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAboutRequest;
use App\Models\About;
use App\Services\CmsImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AboutAdminController extends Controller
{
    public function edit(): View
    {
        $about = About::query()->first() ?? new About;

        return view('admin.about.edit', compact('about'));
    }

    public function update(UpdateAboutRequest $request, CmsImageUploader $uploader): RedirectResponse
    {
        $about = About::query()->first() ?? new About;
        $data = $request->safe()->except(['breadcrumb_image']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['breadcrumb_image'] = $uploader->upload(
            $request->file('breadcrumb_image'),
            'about',
            $about->breadcrumb_image
        );

        About::query()->updateOrCreate(['id' => $about->id ?: 1], $data);

        return redirect()->route('admin.about.edit')->with('success', 'About page updated.');
    }
}
