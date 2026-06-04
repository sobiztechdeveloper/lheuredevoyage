<?php

namespace App\Models;

use App\Models\Concerns\HasCmsMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class WebsiteSetting extends Model
{
    use HasCmsMedia;

    protected $fillable = [
        'company_name', 'company_email', 'company_phone', 'company_address',
        'vat_number', 'registration_number', 'business_hours',
        'logo', 'favicon', 'default_breadcrumb_image',
        'facebook_url', 'instagram_url', 'linkedin_url', 'youtube_url',
        'footer_text', 'copyright_text',
    ];

    public static function cached(): self
    {
        $settings = static::query()->first();

        if ($settings) {
            Cache::put('website_settings', $settings->toArray(), 3600);

            return $settings;
        }

        return new static([
            'company_name' => "L'Heure De Voyage",
        ]);
    }

    public static function clearCache(): void
    {
        Cache::forget('website_settings');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }

    public function getLogoUrlAttribute(): string
    {
        return $this->mediaUrl($this->logo, 'assets/img/logo/logo.png');
    }

    public function getFaviconUrlAttribute(): string
    {
        return $this->mediaUrl($this->favicon, 'assets/img/logo/favicon.png');
    }

    public function getDefaultBreadcrumbImageUrlAttribute(): string
    {
        return $this->mediaUrl(
            $this->default_breadcrumb_image,
            config('page-banners.default', 'assets/img/breadcrumb/01.jpg')
        );
    }

    public function hasCustomLogo(): bool
    {
        return filled($this->logo);
    }
}
