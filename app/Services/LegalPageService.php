<?php

namespace App\Services;

use App\Models\ContactDetail;
use App\Models\LegalPage;
use App\Models\WebsiteSetting;
use Illuminate\Support\Str;

class LegalPageService
{
    public function urlForSlug(string $slug): ?string
    {
        $page = LegalPage::query()->active()->where('slug', $slug)->first();

        return $page ? $page->publicUrl() : null;
    }

    /**
     * @return array<string, string>
     */
    public function activeUrlMap(): array
    {
        return LegalPage::cachedFooterUrlMap();
    }

    public function findActiveBySlug(string $slug): ?LegalPage
    {
        $page = LegalPage::query()->active()->where('slug', $slug)->first();

        if (! $page) {
            return null;
        }

        $page->content = $this->replacePlaceholders($page->content);
        $page->summary = $page->summary ? $this->replacePlaceholders($page->summary) : null;

        return $page;
    }

    public function replacePlaceholders(string $content): string
    {
        $settings = WebsiteSetting::cached();
        $contact = ContactDetail::cached();

        $replacements = [
            '[COMPANY NAME]' => $settings->company_name ?? "L'Heure De Voyage",
            '[COMPANY ADDRESS]' => $settings->company_address ?? $contact->address ?? '',
            '[COMPANY EMAIL]' => $settings->company_email ?? $contact->email ?? '',
            '[COMPANY PHONE]' => $settings->company_phone ?? $contact->phone ?? '',
            '[VAT NUMBER]' => $settings->vat_number ?? '—',
            '[REGISTRATION NUMBER]' => $settings->registration_number ?? '—',
            '[BUSINESS HOURS]' => $settings->business_hours ?? '—',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }

    /**
     * @return array<string, mixed>
     */
    public function companyInformationData(): array
    {
        $settings = WebsiteSetting::cached();
        $contact = ContactDetail::cached();

        return [
            'company_name' => $settings->company_name,
            'address' => $settings->company_address ?: $contact->address,
            'email' => $settings->company_email ?: $contact->email,
            'phone' => $settings->company_phone ?: $contact->phone,
            'whatsapp' => $contact->whatsapp_number,
            'vat_number' => $settings->vat_number,
            'registration_number' => $settings->registration_number,
            'business_hours' => $settings->business_hours,
            'facebook_url' => $settings->facebook_url,
            'instagram_url' => $settings->instagram_url,
            'linkedin_url' => $settings->linkedin_url,
            'youtube_url' => $settings->youtube_url,
        ];
    }

    public function prepareForStorage(array $data, bool $isActive): array
    {
        $data['is_active'] = $isActive;
        $data['slug'] = Str::slug($data['slug'] ?? $data['title']);

        if ($isActive && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (! $isActive) {
            $data['published_at'] = $data['published_at'] ?? null;
        }

        return $data;
    }
}
