<?php

namespace App\Models;

use App\Models\Concerns\HasCmsMedia;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ContactDetail extends Model
{
    use HasCmsMedia;

    protected $fillable = [
        'address', 'phone', 'email', 'google_map_embed', 'whatsapp_number',
        'breadcrumb_image', 'form_title', 'form_subtitle',
    ];

    public static function cached(): self
    {
        $data = Cache::get('contact_details');

        if ($data !== null && ! is_array($data)) {
            Cache::forget('contact_details');
            $data = null;
        }

        if (is_array($data)) {
            if (! empty($data['id'])) {
                return static::query()->find($data['id']) ?? static::make($data);
            }

            return static::make($data);
        }

        $contact = static::query()->first() ?? new static;

        Cache::put('contact_details', $contact->exists ? $contact->toArray() : $contact->getAttributes(), 3600);

        return $contact;
    }

    public static function clearCache(): void
    {
        Cache::forget('contact_details');
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }

    public function getBreadcrumbImageUrlAttribute(): string
    {
        return $this->mediaUrl(
            $this->breadcrumb_image,
            config('page-banners.pages.contact', config('page-banners.default'))
        );
    }

    public function resolvedFormTitle(): string
    {
        return $this->form_title ?: 'Get in Touch';
    }

    public function resolvedFormSubtitle(): string
    {
        return $this->form_subtitle ?: 'Have a question about flights, hotels, packages or an existing booking? Send us a message and our team will respond as soon as possible.';
    }
}
