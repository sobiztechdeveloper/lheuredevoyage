<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateWebsiteSettingRequest;
use App\Models\WebsiteSetting;
use App\Services\CmsImageUploader;
use App\Support\PageBanner;
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
        $existing = WebsiteSetting::query()->first();
        $catalogPageKeys = array_keys(PageBanner::catalogAdminLabels());
        $except = ['logo', 'favicon', 'default_breadcrumb_image'];
        foreach ($catalogPageKeys as $pageKey) {
            $except[] = "breadcrumb_{$pageKey}";
        }

        $data = $request->safe()->except($except);

        $data['logo'] = $uploader->upload($request->file('logo'), 'settings', $existing?->logo);
        $data['favicon'] = $uploader->upload($request->file('favicon'), 'settings', $existing?->favicon);
        $data['default_breadcrumb_image'] = $uploader->upload(
            $request->file('default_breadcrumb_image'),
            'settings',
            $existing?->default_breadcrumb_image
        );

        $pageBreadcrumbs = $existing?->page_breadcrumb_images ?? [];
        foreach ($catalogPageKeys as $pageKey) {
            $field = "breadcrumb_{$pageKey}";
            if ($request->hasFile($field)) {
                $pageBreadcrumbs[$pageKey] = $uploader->upload(
                    $request->file($field),
                    'settings/breadcrumbs',
                    $pageBreadcrumbs[$pageKey] ?? null
                );
            }
        }
        $data['page_breadcrumb_images'] = $pageBreadcrumbs;

        WebsiteSetting::query()->updateOrCreate(['id' => 1], $data);
        WebsiteSetting::clearCache();

        return redirect()->route('admin.settings.edit')->with('success', 'Website settings updated successfully.');
    }
}
