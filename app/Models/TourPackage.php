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
        'slug', 'name', 'title', 'short_description', 'description', 'destination', 'country', 'location',
        'holiday_type', 'duration', 'duration_days', 'duration_nights', 'included_services',
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
            'included_services' => 'array',
            'status' => 'boolean',
            'featured' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function holidayTypeLabel(): string
    {
        if (! $this->holiday_type) {
            return '';
        }

        return config('tourpackage.holiday_types.'.$this->holiday_type, ucfirst(str_replace('_', ' ', $this->holiday_type)));
    }

    public function displayCountry(): string
    {
        return $this->country ?: $this->destination ?: $this->location ?: '';
    }

    public function displayDuration(): string
    {
        if ($this->duration) {
            return $this->duration;
        }

        $parts = [];

        if ($this->duration_days) {
            $parts[] = $this->duration_days.' Days';
        }

        if ($this->duration_nights) {
            $parts[] = $this->duration_nights.' Nights';
        }

        return implode(' / ', $parts);
    }

    /**
     * @return list<string>
     */
    public function includedServiceLabels(): array
    {
        $services = $this->included_services ?? [];

        if (! is_array($services) || $services === []) {
            return [];
        }

        $labels = config('tourpackage.included_services', []);

        return collect($services)
            ->map(fn ($key) => $labels[$key] ?? ucfirst((string) $key))
            ->filter()
            ->values()
            ->all();
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
