<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HotelRoom extends Model
{
    protected $fillable = [
        'hotel_id', 'name', 'room_type', 'description', 'room_size',
        'max_adults', 'max_children', 'bed_type', 'meal_plan',
        'price', 'currency', 'featured_image', 'gallery_json', 'features',
        'is_active', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'gallery_json' => 'array',
            'features' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class);
    }

    public function bookingRequests(): HasMany
    {
        return $this->hasMany(HotelBookingRequest::class, 'room_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->featured_image) {
            return null;
        }

        if (str_starts_with($this->featured_image, 'http') || str_starts_with($this->featured_image, 'assets/')) {
            return asset($this->featured_image);
        }

        return asset('storage/'.$this->featured_image);
    }

    /**
     * @return list<string>
     */
    public function galleryUrls(): array
    {
        $gallery = $this->gallery_json ?? [];

        return collect($gallery)->map(function ($path) {
            if (str_starts_with((string) $path, 'http') || str_starts_with((string) $path, 'assets/')) {
                return asset($path);
            }

            return asset('storage/'.$path);
        })->all();
    }

    public function maxOccupancyLabel(): string
    {
        $parts = [];
        if ($this->max_adults) {
            $parts[] = $this->max_adults.' Adult'.($this->max_adults > 1 ? 's' : '');
        }
        if ($this->max_children) {
            $parts[] = $this->max_children.' Child'.($this->max_children > 1 ? 'ren' : '');
        }

        return implode(', ', $parts) ?: '—';
    }
}
