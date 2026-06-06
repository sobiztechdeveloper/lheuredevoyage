<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlightSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('from-destination') && ! $this->has('from_destination')) {
            $merge['from_destination'] = $this->input('from-destination');
        }

        if ($this->has('to-destination') && ! $this->has('to_destination')) {
            $merge['to_destination'] = $this->input('to-destination');
        }

        if ($this->has('journey-date') && ! $this->has('journey_date')) {
            $merge['journey_date'] = $this->input('journey-date');
        }

        if ($this->has('return-date') && ! $this->has('return_date')) {
            $merge['return_date'] = $this->input('return-date');
        }

        if ($this->has('flight-type') && ! $this->has('trip_type')) {
            $merge['trip_type'] = match ($this->input('flight-type')) {
                'one-way', 'one_way' => 'one_way',
                'round-way', 'round_trip' => 'round_trip',
                'multi-city', 'multi_city' => 'one_way',
                default => $this->input('flight-type'),
            };
        }

        if ($this->has('cabin-class') && ! $this->has('cabin_class')) {
            $merge['cabin_class'] = match (strtolower((string) $this->input('cabin-class'))) {
                'business' => 'business',
                'first class', 'first' => 'first',
                'premium economy' => 'premium_economy',
                default => 'economy',
            };
        }

        foreach (['adult', 'children', 'infant'] as $passengerField) {
            if ($this->has($passengerField) && $this->input($passengerField) !== null && $this->input($passengerField) !== '') {
                $merge[$passengerField] = (int) $this->input($passengerField);
            }
        }

        $merge['trip_type'] = $merge['trip_type'] ?? $this->input('trip_type', 'one_way');
        $merge['from_destination'] = trim((string) ($merge['from_destination'] ?? $this->input('from_destination', $this->input('from-destination', ''))));
        $merge['to_destination'] = trim((string) ($merge['to_destination'] ?? $this->input('to_destination', $this->input('to-destination', ''))));
        $merge['journey_date'] = $merge['journey_date'] ?? $this->input('journey_date', $this->input('journey-date')) ?: now()->addDays(14)->toDateString();
        $merge['return_date'] = $merge['return_date'] ?? $this->input('return_date', $this->input('return-date'));
        $merge['adult'] = (int) ($merge['adult'] ?? $this->input('adult', 1) ?: 1);
        $merge['children'] = (int) ($merge['children'] ?? $this->input('children', 0) ?: 0);
        $merge['infant'] = (int) ($merge['infant'] ?? $this->input('infant', 0) ?: 0);
        $merge['cabin_class'] = $merge['cabin_class'] ?? $this->input('cabin_class', $this->input('cabin-class', 'economy')) ?: 'economy';

        $this->merge($merge);
    }

    public function rules(): array
    {
        return [
            'trip_type' => ['nullable', Rule::in(['one_way', 'round_trip'])],
            'from_destination' => ['nullable', 'string', 'max:255'],
            'to_destination' => ['nullable', 'string', 'max:255'],
            'journey_date' => ['nullable', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:journey_date'],
            'adult' => ['nullable', 'integer', 'min:1', 'max:9'],
            'children' => ['nullable', 'integer', 'min:0', 'max:9'],
            'infant' => ['nullable', 'integer', 'min:0', 'max:9'],
            'cabin_class' => ['nullable', Rule::in(['economy', 'premium_economy', 'business', 'first'])],
        ];
    }

    public function isBrowseOnly(): bool
    {
        return trim((string) $this->input('from_destination', '')) === ''
            && trim((string) $this->input('to_destination', '')) === '';
    }
}
