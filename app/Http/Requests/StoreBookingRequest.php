<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'bookable_type' => ['required', 'string'],
            'bookable_slug' => ['required', 'string'],
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_email' => ['required', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:50'],
            'travel_date' => ['nullable', 'date'],
            'adult' => ['required', 'integer', 'min:1', 'max:20'],
            'children' => ['nullable', 'integer', 'min:0', 'max:20'],
            'infant' => ['nullable', 'integer', 'min:0', 'max:10'],
            'notes' => ['nullable', 'string', 'max:2000'],
            'accept_conditions' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'accept_conditions.accepted' => 'Please accept the Terms & Conditions and Privacy Policy.',
        ];
    }
}
