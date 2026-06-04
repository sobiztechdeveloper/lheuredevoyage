<?php

namespace App\Models;

use App\Models\Concerns\HasCmsMedia;
use App\Models\Concerns\IsCatalogProduct;
use App\Models\Master\InsuranceCoverageType;
use App\Models\Master\InsuranceType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class TravelInsurance extends Model
{
    use HasCmsMedia, IsCatalogProduct;

    protected $fillable = [
        'slug', 'insurance_company', 'name', 'plan_code', 'plan_type', 'title',
        'short_description', 'description', 'coverage', 'coverage_type', 'duration_days', 'location',
        'price', 'price_unit', 'image', 'featured_image', 'logo',
        'brochure_pdf', 'policy_wording_pdf', 'terms_pdf',
        'medical_coverage_amount', 'emergency_medical_expenses', 'hospitalization',
        'emergency_evacuation', 'medical_repatriation', 'trip_cancellation', 'trip_interruption',
        'flight_delay', 'baggage_delay', 'baggage_loss', 'missed_connection',
        'personal_liability', 'legal_assistance',
        'covid_coverage', 'adventure_sports_coverage', 'winter_sports_coverage', 'coverage_currency',
        'min_age', 'max_age', 'nationality_restrictions', 'covered_regions', 'covered_countries',
        'schengen_covered', 'worldwide_covered', 'student_eligible', 'family_eligible',
        'base_premium', 'premium_currency', 'price_per_person', 'price_per_family',
        'annual_price', 'student_price', 'child_price', 'senior_price', 'sort_order',
        'rating', 'review_count', 'status', 'featured', 'is_featured', 'is_active',
        'created_by', 'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'base_premium' => 'decimal:2',
            'medical_coverage_amount' => 'decimal:2',
            'emergency_medical_expenses' => 'decimal:2',
            'hospitalization' => 'decimal:2',
            'emergency_evacuation' => 'decimal:2',
            'medical_repatriation' => 'decimal:2',
            'trip_cancellation' => 'decimal:2',
            'trip_interruption' => 'decimal:2',
            'flight_delay' => 'decimal:2',
            'baggage_delay' => 'decimal:2',
            'baggage_loss' => 'decimal:2',
            'missed_connection' => 'decimal:2',
            'personal_liability' => 'decimal:2',
            'legal_assistance' => 'decimal:2',
            'annual_price' => 'decimal:2',
            'student_price' => 'decimal:2',
            'child_price' => 'decimal:2',
            'senior_price' => 'decimal:2',
            'rating' => 'decimal:1',
            'covid_coverage' => 'boolean',
            'adventure_sports_coverage' => 'boolean',
            'winter_sports_coverage' => 'boolean',
            'schengen_covered' => 'boolean',
            'worldwide_covered' => 'boolean',
            'student_eligible' => 'boolean',
            'family_eligible' => 'boolean',
            'price_per_person' => 'boolean',
            'price_per_family' => 'boolean',
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
        return $this->hasMany(InsuranceBookingRequest::class);
    }

    public function benefits(): HasMany
    {
        return $this->hasMany(InsurancePlanBenefit::class)->orderBy('sort_order');
    }

    public function exclusions(): HasMany
    {
        return $this->hasMany(InsurancePlanExclusion::class)->orderBy('sort_order');
    }

    public function galleryImages(): HasMany
    {
        return $this->hasMany(InsurancePlanGalleryImage::class)->orderBy('sort_order');
    }

    public function insuranceTypes(): BelongsToMany
    {
        return $this->belongsToMany(InsuranceType::class, 'insurance_type_travel_insurance');
    }

    public function coverageTypes(): BelongsToMany
    {
        return $this->belongsToMany(InsuranceCoverageType::class, 'coverage_type_travel_insurance');
    }

    public function displayPlanName(): string
    {
        return $this->name ?: $this->title ?: 'Insurance Plan';
    }

    public function displayCompany(): string
    {
        return $this->insurance_company ?: config('app.name', "L'Heure De Voyage");
    }

    public function planTypeLabel(): string
    {
        return config('insurance.plan_types.'.$this->plan_type, $this->plan_type ?: 'Travel Insurance');
    }

    public function plan_type_label(): string
    {
        return $this->planTypeLabel();
    }

    public function featuredImageUrl(): string
    {
        return $this->mediaUrl($this->featured_image ?? $this->image);
    }

    public function logoUrl(): string
    {
        return $this->mediaUrl($this->logo);
    }

    public function brochureUrl(): ?string
    {
        return $this->pdfUrl($this->brochure_pdf);
    }

    public function policyWordingUrl(): ?string
    {
        return $this->pdfUrl($this->policy_wording_pdf);
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

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/'.$path);
    }

    public function formattedMedicalCoverage(): ?string
    {
        if (! $this->medical_coverage_amount) {
            return null;
        }

        $currency = $this->coverage_currency ?? 'CHF';

        return $currency.' '.number_format((float) $this->medical_coverage_amount, 0, '.', "'");
    }

    public function displayPremium(): string
    {
        $amount = (float) ($this->base_premium ?? $this->price ?? 0);
        $currency = $this->premium_currency ?? 'CHF';

        return $currency.' '.number_format($amount, 2);
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->displayPremium();
    }

    public function getPriceUnitAttribute(?string $value): string
    {
        if ($this->price_per_person) {
            return 'per person';
        }

        if ($this->price_per_family) {
            return 'per family';
        }

        return $value ?: 'plan';
    }

    /**
     * @return array<int, string>
     */
    public function keyBenefitsList(int $limit = 3): array
    {
        if ($this->relationLoaded('benefits')) {
            return $this->benefits->take($limit)->pluck('title')->filter()->values()->all();
        }

        return $this->benefits()->limit($limit)->pluck('title')->filter()->values()->all();
    }
}
