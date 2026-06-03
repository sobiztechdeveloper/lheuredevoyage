<?php

namespace App\Models;

use App\Models\Concerns\IsCatalogProduct;
use App\Models\Master\InsuranceCoverageType;
use App\Models\Master\InsuranceType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TravelInsurance extends Model
{
    use IsCatalogProduct;

    protected $fillable = [
        'slug', 'name', 'title', 'short_description', 'description',
        'coverage', 'coverage_type', 'duration_days', 'location',
        'price', 'price_unit', 'image', 'featured_image',
        'rating', 'review_count', 'status', 'featured', 'is_featured', 'is_active',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rating' => 'decimal:1',
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

    public function insuranceTypes(): BelongsToMany
    {
        return $this->belongsToMany(InsuranceType::class, 'insurance_type_travel_insurance');
    }

    public function coverageTypes(): BelongsToMany
    {
        return $this->belongsToMany(InsuranceCoverageType::class, 'coverage_type_travel_insurance');
    }
}
