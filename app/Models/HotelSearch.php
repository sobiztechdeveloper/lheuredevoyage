<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HotelSearch extends Model
{
    protected $fillable = [
        'user_id', 'provider', 'destination', 'journey_date', 'return_date',
        'adult', 'children', 'infant', 'rooms', 'room_type', 'status',
    ];

    protected function casts(): array
    {
        return [
            'journey_date' => 'date',
            'return_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(HotelSearchResult::class);
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchCriteria(): array
    {
        return [
            'destination' => $this->destination,
            'journey_date' => $this->journey_date?->format('Y-m-d'),
            'return_date' => $this->return_date?->format('Y-m-d'),
            'adult' => $this->adult,
            'children' => $this->children,
            'infant' => $this->infant,
            'rooms' => $this->rooms,
            'room_type' => $this->room_type,
        ];
    }

    /**
     * Query string for hotel detail / booking links.
     *
     * @return array<string, string|int>
     */
    public function bookingQueryParams(): array
    {
        $params = [
            'hotel_search' => $this->id,
            'adult' => $this->adult,
            'children' => $this->children,
            'infant' => $this->infant,
            'room' => $this->rooms,
        ];

        if ($this->journey_date) {
            $params['journey-date'] = $this->journey_date->format(config('date.display'));
        }

        if ($this->return_date) {
            $params['return-date'] = $this->return_date->format(config('date.display'));
        }

        if ($this->room_type) {
            $params['room-type'] = $this->room_type;
        }

        return $params;
    }
}
