<?php

namespace App\Models;

use App\Models\Concerns\HasCmsMedia;
use App\Models\Concerns\IsCatalogProduct;
use App\Models\Master\CruiseCategory;
use App\Models\Master\CruiseFacility;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Cruise extends Model
{
    use HasCmsMedia, IsCatalogProduct;

    protected $fillable = [
        'slug', 'cruise_code', 'name', 'title', 'cruise_line', 'ship_name', 'ship_class',
        'ship_capacity', 'launch_year', 'cruise_region', 'departure_port', 'arrival_port',
        'destination', 'location', 'duration', 'duration_days', 'duration_nights',
        'short_description', 'description', 'price', 'price_unit', 'image', 'featured_image',
        'brochure_pdf', 'deck_plan_pdf', 'terms_pdf', 'included_services', 'not_included_services',
        'gallery_json', 'rating', 'review_count', 'sort_order', 'status', 'featured',
        'is_featured', 'is_active', 'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rating' => 'decimal:1',
            'gallery_json' => 'array',
            'included_services' => 'array',
            'not_included_services' => 'array',
            'status' => 'boolean',
            'featured' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public static function generateSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;

        while (static::query()
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->where('slug', $slug)
            ->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function bookingRequests(): HasMany
    {
        return $this->hasMany(CruiseBookingRequest::class);
    }

    public function itineraryDays(): HasMany
    {
        return $this->hasMany(CruiseItineraryDay::class)->orderBy('sort_order')->orderBy('day_number');
    }

    public function cabins(): HasMany
    {
        return $this->hasMany(CruiseCabin::class)->orderBy('sort_order');
    }

    public function activeCabins(): HasMany
    {
        return $this->cabins();
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(CruiseGalleryImage::class)->orderBy('sort_order');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(CruiseCategory::class, 'cruise_category_cruise');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(CruiseFacility::class, 'cruise_cruise_facility');
    }

    public function displayName(): string
    {
        return $this->name ?: $this->title;
    }

    public function regionLabel(): string
    {
        return config('cruise.regions.'.$this->cruise_region, $this->cruise_region ?? '—');
    }

    public function startingPriceDisplay(): string
    {
        $cabinMin = $this->relationLoaded('cabins')
            ? $this->cabins->min('starting_price')
            : $this->cabins()->min('starting_price');

        $amount = (float) ($cabinMin ?? $this->price ?? 0);

        return format_money($amount, currency_service()->catalogSource(), 2);
    }

    public function brochureUrl(): ?string
    {
        return $this->pdfUrl($this->brochure_pdf);
    }

    public function deckPlanUrl(): ?string
    {
        return $this->pdfUrl($this->deck_plan_pdf);
    }

    public function termsPdfUrl(): ?string
    {
        return $this->pdfUrl($this->terms_pdf);
    }

    protected function pdfUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        return str_starts_with($path, 'http') ? $path : asset('storage/'.$path);
    }
}
