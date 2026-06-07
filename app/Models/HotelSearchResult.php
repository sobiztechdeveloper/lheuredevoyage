<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelSearchResult extends Model
{
    protected $fillable = [
        'hotel_search_id', 'hotel_id', 'slug', 'title', 'location',
        'star_rating', 'rating', 'review_count', 'price', 'featured_image', 'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rating' => 'decimal:1',
            'is_featured' => 'boolean',
        ];
    }

    public function hotelSearch(): BelongsTo
    {
        return $this->belongsTo(HotelSearch::class);
    }

    public function catalogHotel(): BelongsTo
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }

    public function getImageUrlAttribute(): string
    {
        $hotel = $this->relationLoaded('catalogHotel') ? $this->catalogHotel : null;
        if ($hotel) {
            return $hotel->image_url;
        }

        $path = $this->featured_image;
        if (! $path) {
            return asset('assets/img/logo/logo.png');
        }

        if (str_starts_with($path, 'http') || str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return asset('storage/'.$path);
    }

    public function getFormattedPriceAttribute(): string
    {
        return format_money((float) $this->price, currency_service()->catalogSource());
    }

    public function starCount(): int
    {
        return (int) $this->star_rating;
    }
}
