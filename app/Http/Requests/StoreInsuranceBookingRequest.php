<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInsuranceBookingRequest extends FormRequest
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
            'travel_insurance_id' => ['required', 'exists:travel_insurances,id'],
            'destination' => ['nullable', 'string', 'max:255'],
            'travel_start' => ['required', 'date', 'after_or_equal:today'],
            'travel_end' => ['required', 'date', 'after:travel_start'],
            'coverage_type' => ['nullable', 'string', 'max:120'],
            'pre_existing_conditions' => ['nullable', 'boolean'],
            'medical_notes' => ['nullable', 'string', 'max:5000'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:5000'],
            'country' => ['nullable', 'string', 'max:100'],
            'estimated_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],

            'travelers' => ['required', 'array', 'min:1', 'max:20'],
            'travelers.*.title' => ['nullable', 'string', 'max:10'],
            'travelers.*.first_name' => ['required', 'string', 'max:100'],
            'travelers.*.last_name' => ['required', 'string', 'max:100'],
            'travelers.*.date_of_birth' => ['nullable', 'date', 'before:today'],
            'travelers.*.nationality' => ['nullable', 'string', 'max:100'],
            'travelers.*.passport_number' => ['nullable', 'string', 'max:60'],
            'accept_conditions' => ['accepted'],
        ];
    }
}
