<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateWebsiteSettingRequest;
use App\Models\WebsiteSetting;
use App\Services\CmsImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WebsiteSettingController extends Controller
{
    public function edit(): View
    {
        $settings = WebsiteSetting::query()->first() ?? new WebsiteSetting;

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(UpdateWebsiteSettingRequest $request, CmsImageUploader $uploader): RedirectResponse
    {
        $settings = WebsiteSetting::query()->first() ?? new WebsiteSetting;
        $data = $request->validated();

        $data['logo'] = $uploader->upload($request->file('logo'), 'settings', $settings->logo);
        $data['favicon'] = $uploader->upload($request->file('favicon'), 'settings', $settings->favicon);

        $settings->fill($data);
        $settings->save();

        return redirect()->route('admin.settings.edit')->with('success', 'Website settings updated successfully.');
    }
}
