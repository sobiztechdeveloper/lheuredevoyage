<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ContactDetail extends Model
{
    protected $fillable = [
        'address', 'phone', 'email', 'google_map_embed', 'whatsapp_number',
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
}
