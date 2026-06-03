<?php

namespace App\Models;

use App\Models\Concerns\IsCatalogProduct;
use App\Models\Master\PackageCategory;
use App\Models\Master\PackageTheme;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TourPackage extends Model
{
    use IsCatalogProduct;

    protected $fillable = [
        'slug', 'name', 'title', 'short_description', 'description', 'destination', 'location',
        'duration', 'duration_days', 'price', 'price_unit', 'image', 'featured_image', 'gallery_json',
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
        return $this->belongsToMany(PackageCategory::class, 'package_category_tour_package');
    }

    public function themes(): BelongsToMany
    {
        return $this->belongsToMany(PackageTheme::class, 'package_theme_tour_package');
    }
}
