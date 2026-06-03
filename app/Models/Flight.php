<?php

namespace App\Models;

use App\Models\Concerns\HasCatalogAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Flight extends Model
{
    use HasCatalogAttributes;

    protected $fillable = [
        'slug', 'title', 'description', 'airline', 'flight_number', 'departure_city', 'arrival_city',
        'location', 'departure_time', 'arrival_time', 'duration', 'flight_class', 'stops',
        'refundable_type', 'baggage_kg', 'price', 'price_unit', 'image', 'rating', 'review_count',
        'is_featured', 'is_active',
    ];

    public function setNameAttribute(?string $value): void
    {
        if ($value !== null) {
            $this->attributes['title'] = $value;
        }
    }

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'rating' => 'decimal:1',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function bookings(): MorphMany
    {
        return $this->morphMany(Booking::class, 'bookable');
    }

    public function normalizedCabinClass(): string
    {
        $key = Str::slug(strtolower((string) $this->flight_class), '_');

        return match ($key) {
            'premium_economy', 'premium-economy' => 'premium_economy',
            'business' => 'business',
            'first_class', 'first' => 'first',
            default => 'economy',
        };
    }

    public function normalizedRefundableType(): string
    {
        $key = Str::slug(strtolower((string) $this->refundable_type), '_');

        return match ($key) {
            'refundable' => 'refundable',
            'non_refundable', 'non-refundable' => 'non_refundable',
            default => 'as_per_rules',
        };
    }

    public function routeLabel(): string
    {
        $from = $this->departure_city ?: '—';
        $to = $this->arrival_city ?: '—';

        return "{$from} → {$to}";
    }
}
