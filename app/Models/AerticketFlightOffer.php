<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AerticketFlightOffer extends Model
{
    protected $fillable = [
        'flight_search_id', 'external_offer_id', 'airline', 'flight_number',
        'from_destination', 'to_destination', 'departure_at', 'arrival_at',
        'duration', 'stops', 'cabin_class', 'price', 'currency',
        'summary', 'detail_response', 'fare_rules_response',
        'detail_fetched_at', 'fare_rules_fetched_at',
    ];

    protected function casts(): array
    {
        return [
            'departure_at' => 'datetime',
            'arrival_at' => 'datetime',
            'price' => 'decimal:2',
            'summary' => 'array',
            'detail_response' => 'array',
            'fare_rules_response' => 'array',
            'detail_fetched_at' => 'datetime',
            'fare_rules_fetched_at' => 'datetime',
        ];
    }

    public function flightSearch(): BelongsTo
    {
        return $this->belongsTo(FlightSearch::class);
    }

    public function formattedDisplayPrice(int $decimals = 0): string
    {
        $source = strtoupper((string) ($this->currency ?: currency_service()->serpapiSource()));

        return format_money((float) $this->price, $source, $decimals);
    }
}
