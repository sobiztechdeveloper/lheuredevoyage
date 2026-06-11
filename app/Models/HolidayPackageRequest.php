<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class HolidayPackageRequest extends Model
{
    public const STATUSES = [
        'new',
        'contacted',
        'quoted',
        'booked',
        'closed',
    ];

    protected $fillable = [
        'reference_number',
        'status',
        'locale',
        'departure_airport',
        'destination',
        'earliest_departure_date',
        'latest_return_date',
        'duration',
        'adults',
        'children',
        'child_ages',
        'budget_amount',
        'budget_currency',
        'full_name',
        'email',
        'phone',
        'country',
        'room_types',
        'board_types',
        'preferred_airline',
        'travel_class',
        'outbound_time_preference',
        'return_time_preference',
        'direct_flight_only',
        'transfer_allowed',
        'rail_and_fly',
        'hotel_category',
        'hotel_recommendation',
        'sea_view',
        'hotel_features',
        'beach_preferences',
        'sports',
        'wellness',
        'kids_club',
        'babysitting',
        'room_amenities',
        'additional_notes',
        'agent_notes',
    ];

    protected function casts(): array
    {
        return [
            'earliest_departure_date' => 'date',
            'latest_return_date' => 'date',
            'child_ages' => 'array',
            'budget_amount' => 'decimal:2',
            'room_types' => 'array',
            'board_types' => 'array',
            'hotel_features' => 'array',
            'beach_preferences' => 'array',
            'sports' => 'array',
            'wellness' => 'array',
            'room_amenities' => 'array',
            'direct_flight_only' => 'boolean',
            'transfer_allowed' => 'boolean',
            'rail_and_fly' => 'boolean',
            'kids_club' => 'boolean',
            'babysitting' => 'boolean',
        ];
    }

    public static function generateReference(): string
    {
        $date = now()->format('Ymd');

        do {
            $suffix = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $reference = "HP-{$date}-{$suffix}";
        } while (static::query()->where('reference_number', $reference)->exists());

        return $reference;
    }

    public function scopeSearchTerm(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $inner) use ($term) {
            $inner->where('reference_number', 'like', "%{$term}%")
                ->orWhere('full_name', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%")
                ->orWhere('destination', 'like', "%{$term}%");
        });
    }

    public function statusLabel(): string
    {
        return __('holiday_package_request.statuses.'.$this->status);
    }

    /**
     * @return list<string>
     */
    public function translatedOptionList(string $group, ?array $values = null): array
    {
        $values = $values ?? [];
        $labels = holiday_package_request_config()['option_labels'][$group] ?? [];

        return collect($values)
            ->map(function ($value) use ($group, $labels) {
                if (isset($labels[$value])) {
                    return $labels[$value];
                }

                $translation = __('holiday_package_request.options.'.$group.'.'.$value);

                return $translation !== 'holiday_package_request.options.'.$group.'.'.$value
                    ? $translation
                    : (string) $value;
            })
            ->all();
    }
}
