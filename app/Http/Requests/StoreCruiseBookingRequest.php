<?php

namespace App\Http\Requests;

use App\Models\CruiseBookingRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCruiseBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'cruise_id' => ['required', 'exists:cruises,id'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['nullable', 'date', 'after_or_equal:departure_date'],
            'cabin_type' => ['nullable', 'string', 'max:120'],
            'adults' => ['required', 'integer', 'min:1', 'max:20'],
            'children' => ['nullable', 'integer', 'min:0', 'max:20'],
            'infants' => ['nullable', 'integer', 'min:0', 'max:10'],
            'contact_name' => ['required', 'string', 'max:255'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'dining_preference' => ['nullable', 'string', 'max:120'],
            'dietary_requirements' => ['nullable', 'string', 'max:5000'],
            'wheelchair_assistance' => ['nullable', 'boolean'],
            'medical_conditions' => ['nullable', 'string', 'max:5000'],
            'additional_notes' => ['nullable', 'string', 'max:5000'],
            'estimated_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],

            'passengers' => ['required', 'array', 'min:1', 'max:20'],
            'passengers.*.title' => ['nullable', 'string', 'max:10'],
            'passengers.*.first_name' => ['required', 'string', 'max:100'],
            'passengers.*.last_name' => ['required', 'string', 'max:100'],
            'passengers.*.passenger_type' => ['required', Rule::in(['adult', 'child', 'infant'])],
            'passengers.*.gender' => ['nullable', Rule::in(['male', 'female', 'other'])],
            'passengers.*.date_of_birth' => ['nullable', 'date', 'before:today'],
            'passengers.*.nationality' => ['nullable', 'string', 'max:100'],
            'passengers.*.passport_number' => ['nullable', 'string', 'max:60'],
            'passengers.*.passport_expiry' => ['nullable', 'date'],
            'passengers.*.passport_country' => ['nullable', 'string', 'max:100'],
            'passengers.*.passport_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'accept_conditions' => ['accepted'],
        ];
    }
}
