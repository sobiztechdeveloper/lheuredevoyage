<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FlightSearch extends Model
{
    protected $fillable = [
        'user_id', 'provider', 'external_session_id', 'trip_type',
        'from_destination', 'to_destination', 'journey_date', 'return_date',
        'adult', 'children', 'infant', 'cabin_class', 'status',
        'search_payload', 'search_response',
    ];

    protected function casts(): array
    {
        return [
            'journey_date' => 'date',
            'return_date' => 'date',
            'search_payload' => 'array',
            'search_response' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(FlightSearchResult::class);
    }

    public function aerticketOffers(): HasMany
    {
        return $this->hasMany(AerticketFlightOffer::class);
    }

    public function usesAerticket(): bool
    {
        return $this->provider === 'aerticket';
    }

    public function usesSerpapi(): bool
    {
        return $this->provider === 'serpapi';
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchCriteria(): array
    {
        return [
            'trip_type' => $this->trip_type,
            'from_destination' => $this->from_destination,
            'to_destination' => $this->to_destination,
            'journey_date' => $this->journey_date?->format('Y-m-d'),
            'return_date' => $this->return_date?->format('Y-m-d'),
            'adult' => $this->adult,
            'children' => $this->children,
            'infant' => $this->infant,
            'cabin_class' => $this->cabin_class,
        ];
    }
}
