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

        $merge['destination'] = trim((string) ($this->input('destination', '')));
        $merge['journey_date'] = normalize_user_date(
            $merge['journey_date'] ?? $this->input('journey_date', $this->input('journey-date'))
        ) ?: now()->addDays(7)->toDateString();
        $merge['return_date'] = normalize_user_date(
            $merge['return_date'] ?? $this->input('return_date', $this->input('return-date'))
        );
        $merge['adult'] = (int) ($this->input('adult', 2) ?: 2);
        $merge['children'] = (int) ($this->input('children', 0) ?: 0);
        $merge['infant'] = (int) ($this->input('infant', 0) ?: 0);
        $merge['rooms'] = (int) ($merge['rooms'] ?? $this->input('rooms', $this->input('room', 1)) ?: 1);
        $merge['room_type'] = $merge['room_type'] ?? $this->input('room_type', $this->input('room-type'));

        $this->merge($merge);
    }

    public function rules(): array
    {
        return [
            'destination' => ['nullable', 'string', 'max:255'],
            'journey_date' => ['nullable', 'date'],
            'return_date' => ['nullable', 'date', 'after_or_equal:journey_date'],
            'adult' => ['nullable', 'integer', 'min:1', 'max:9'],
            'children' => ['nullable', 'integer', 'min:0', 'max:9'],
            'infant' => ['nullable', 'integer', 'min:0', 'max:9'],
            'rooms' => ['nullable', 'integer', 'min:1', 'max:9'],
            'room_type' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function isBrowseOnly(): bool
    {
        return trim((string) $this->input('destination', '')) === '';
    }
}
