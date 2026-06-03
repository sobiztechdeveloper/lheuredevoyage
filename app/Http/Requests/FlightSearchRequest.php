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

        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'trip_type' => ['required', Rule::in(['one_way', 'round_trip'])],
            'from_destination' => ['required', 'string', 'max:255'],
            'to_destination' => ['required', 'string', 'max:255'],
            'journey_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['nullable', 'date', 'after_or_equal:journey_date', 'required_if:trip_type,round_trip'],
            'adult' => ['required', 'integer', 'min:1', 'max:9'],
            'children' => ['nullable', 'integer', 'min:0', 'max:9'],
            'infant' => ['nullable', 'integer', 'min:0', 'max:9'],
            'cabin_class' => ['required', Rule::in(['economy', 'premium_economy', 'business', 'first'])],
        ];
    }
}
