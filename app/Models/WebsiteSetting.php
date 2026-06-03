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
        'logo', 'favicon', 'facebook_url', 'instagram_url', 'linkedin_url', 'youtube_url',
        'footer_text', 'copyright_text',
    ];

    public static function cached(): self
    {
        $data = Cache::get('website_settings');

        if ($data !== null && ! is_array($data)) {
            Cache::forget('website_settings');
            $data = null;
        }

        if (is_array($data)) {
            if (! empty($data['id'])) {
                return static::query()->find($data['id']) ?? static::make($data);
            }

            return static::make($data);
        }

        $settings = static::query()->first() ?? new static([
            'company_name' => "L'Heure De Voyage",
        ]);

        Cache::put('website_settings', $settings->exists ? $settings->toArray() : $settings->getAttributes(), 3600);

        return $settings;
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
}
