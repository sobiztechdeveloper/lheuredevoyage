<?php

namespace App\Models;

use App\Models\Concerns\IsCatalogProduct;
use App\Models\Master\HotelBeachType;
use App\Models\Master\HotelFacility;
use App\Models\Master\HotelSport;
use App\Models\Master\HotelWellness;
use App\Models\Master\MealPlan;
use App\Models\Master\RoomFacility;
use App\Models\Master\RoomType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Hotel extends Model
{
    use IsCatalogProduct;

    protected $fillable = [
        'slug', 'name', 'title', 'short_description', 'description', 'location',
        'stars', 'star_rating', 'price', 'price_unit', 'image', 'featured_image', 'gallery_json',
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

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(HotelFacility::class, 'hotel_facility_hotel');
    }

    public function sports(): BelongsToMany
    {
        return $this->belongsToMany(HotelSport::class, 'hotel_hotel_sport');
    }

    public function wellnesses(): BelongsToMany
    {
        return $this->belongsToMany(HotelWellness::class, 'hotel_hotel_wellness');
    }

    public function beachTypes(): BelongsToMany
    {
        return $this->belongsToMany(HotelBeachType::class, 'hotel_hotel_beach_type');
    }

    public function roomTypes(): BelongsToMany
    {
        return $this->belongsToMany(RoomType::class, 'hotel_room_type');
    }

    public function roomFacilities(): BelongsToMany
    {
        return $this->belongsToMany(RoomFacility::class, 'hotel_room_facility');
    }

    public function mealPlans(): BelongsToMany
    {
        return $this->belongsToMany(MealPlan::class, 'hotel_meal_plan');
    }
}
