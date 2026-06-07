<?php

namespace App\Http\Requests\Admin;

use App\Support\PageBanner;

class UpdateWebsiteSettingRequest extends AdminFormRequest
{

    public function rules(): array
    {
        $catalogBreadcrumbRules = collect(PageBanner::catalogAdminLabels())
            ->keys()
            ->mapWithKeys(fn (string $key) => [
                "breadcrumb_{$key}" => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:4096'],
            ])
            ->all();

        return [
            'company_name' => ['nullable', 'string', 'max:255'],
            'company_email' => ['nullable', 'email', 'max:255'],
            'company_phone' => ['nullable', 'string', 'max:50'],
            'company_address' => ['nullable', 'string', 'max:500'],
            'vat_number' => ['nullable', 'string', 'max:100'],
            'registration_number' => ['nullable', 'string', 'max:100'],
            'business_hours' => ['nullable', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,svg', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,ico', 'max:1024'],
            'default_breadcrumb_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:4096'],
            'facebook_url' => ['nullable', 'url', 'max:255'],
            'instagram_url' => ['nullable', 'url', 'max:255'],
            'linkedin_url' => ['nullable', 'url', 'max:255'],
            'youtube_url' => ['nullable', 'url', 'max:255'],
            'footer_text' => ['nullable', 'string', 'max:2000'],
            ...$catalogBreadcrumbRules,
        ];
    }
}
