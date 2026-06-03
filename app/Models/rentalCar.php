<?php

namespace App\Models;

use App\Models\Concerns\IsCatalogProduct;
use App\Models\Master\VehicleFeature;
use App\Models\Master\VehicleType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RentalCar extends Model
{
    use IsCatalogProduct;

    protected $fillable = [
        'slug', 'name', 'title', 'short_description', 'description',
        'vehicle_type', 'car_type', 'passenger_capacity', 'seats', 'transmission', 'location',
        'price', 'price_per_day', 'price_unit', 'image', 'featured_image', 'gallery_json',
        'rating', 'review_count', 'status', 'featured', 'is_featured', 'is_active',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'price_per_day' => 'decimal:2',
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

    public function vehicleTypes(): BelongsToMany
    {
        return $this->belongsToMany(VehicleType::class, 'rental_car_vehicle_type');
    }

    public function vehicleFeatures(): BelongsToMany
    {
        return $this->belongsToMany(VehicleFeature::class, 'rental_car_vehicle_feature');
    }
}
