<?php

namespace App\Models;

use App\Models\Concerns\IsCatalogProduct;
use App\Models\Master\CruiseCategory;
use App\Models\Master\CruiseFacility;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Cruise extends Model
{
    use IsCatalogProduct;

    protected $fillable = [
        'slug', 'name', 'title', 'short_description', 'description', 'ship_name',
        'departure_port', 'destination', 'location', 'duration', 'duration_days',
        'price', 'price_unit', 'image', 'featured_image', 'gallery_json',
        'rating', 'review_count', 'status', 'featured', 'is_featured', 'is_active',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rating' => 'decimal:1',
            'gallery_json' => 'array',
            'status' => 'boolean',
            'featured' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CruiseCategory::class, 'cruise_category_cruise');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(CruiseFacility::class, 'cruise_cruise_facility');
    }
}
