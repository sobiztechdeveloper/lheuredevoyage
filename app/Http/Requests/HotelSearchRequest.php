<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HotelSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [];

        if ($this->has('journey-date') && ! $this->has('journey_date')) {
            $merge['journey_date'] = $this->input('journey-date');
        }

        if ($this->has('return-date') && ! $this->has('return_date')) {
            $merge['return_date'] = $this->input('return-date');
        }

        if ($this->has('room') && ! $this->has('rooms')) {
            $merge['rooms'] = $this->input('room');
        }

        if ($this->has('room-type') && ! $this->has('room_type')) {
            $merge['room_type'] = $this->input('room-type');
        }

        if ($merge !== []) {
            $this->merge($merge);
        }
    }

    public function rules(): array
    {
        return [
            'destination' => ['required', 'string', 'max:255'],
            'journey_date' => ['required', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:journey_date'],
            'adult' => ['required', 'integer', 'min:1', 'max:9'],
            'children' => ['nullable', 'integer', 'min:0', 'max:9'],
            'infant' => ['nullable', 'integer', 'min:0', 'max:9'],
            'rooms' => ['required', 'integer', 'min:1', 'max:9'],
            'room_type' => ['nullable', 'string', 'max:100'],
        ];
    }
}
