<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightSearchResult extends Model
{
    protected $fillable = [
        'flight_search_id', 'flight_id', 'airline', 'flight_number', 'from_destination', 'to_destination',
        'departure_at', 'arrival_at', 'duration', 'stops', 'cabin_class', 'price', 'currency',
        'refundable_type', 'baggage_kg',
    ];

    protected function casts(): array
    {
        return [
            'departure_at' => 'datetime',
            'arrival_at' => 'datetime',
            'price' => 'decimal:2',
        ];
    }

    public function flightSearch(): BelongsTo
    {
        return $this->belongsTo(FlightSearch::class);
    }

    public function catalogFlight(): BelongsTo
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

    public function stopLabel(): string
    {
        return match ((int) $this->stops) {
            0 => 'Non Stop',
            1 => '1 Stop',
            2 => '2 Stop',
            default => $this->stops.' Stop',
        };
    }

    public function refundableLabel(): string
    {
        return match ($this->refundable_type) {
            'refundable' => 'Refundable',
            'non_refundable' => 'Non Refundable',
            default => 'As Per Rules',
        };
    }

    public function destinationCode(string $destination): string
    {
        $clean = preg_replace('/[^A-Za-z]/', '', $destination) ?? '';

        return strtoupper(substr($clean, 0, 3)) ?: strtoupper(substr($destination, 0, 3));
    }

    public function cabinClassLabel(): string
    {
        return match ($this->cabin_class) {
            'premium_economy' => 'Premium Economy',
            'business' => 'Business',
            'first' => 'First Class',
            default => 'Economy',
        };
    }

    public function airlineInitials(): string
    {
        $words = preg_split('/\s+/', trim((string) $this->airline)) ?: [];

        if (count($words) >= 2) {
            return strtoupper(substr($words[0], 0, 1).substr($words[1], 0, 1));
        }

        return strtoupper(substr((string) $this->airline, 0, 2));
    }

    public static function destinationCodeFrom(?string $destination): string
    {
        if ($destination === null || $destination === '') {
            return '';
        }

        $clean = preg_replace('/[^A-Za-z]/', '', $destination) ?? '';

        return strtoupper(substr($clean, 0, 3)) ?: strtoupper(substr($destination, 0, 3));
    }
}
