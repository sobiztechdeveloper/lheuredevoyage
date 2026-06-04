<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCruiseQuoteRequest extends FormRequest
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
            'cruise_cabin_id' => ['nullable', 'exists:cruise_cabins,id'],
            'departure_date' => ['required', 'date', 'after_or_equal:today'],
            'return_date' => ['nullable', 'date', 'after_or_equal:departure_date'],
            'adults' => ['required', 'integer', 'min:1', 'max:20'],
            'children' => ['nullable', 'integer', 'min:0', 'max:20'],
            'infants' => ['nullable', 'integer', 'min:0', 'max:10'],
            'contact_title' => ['nullable', 'string', 'max:10'],
            'contact_first_name' => ['required', 'string', 'max:100'],
            'contact_last_name' => ['required', 'string', 'max:100'],
            'contact_gender' => ['nullable', 'string', 'max:20'],
            'contact_date_of_birth' => ['required', 'date', 'before:today'],
            'contact_nationality' => ['required', 'string', 'max:100'],
            'contact_passport_number' => ['required', 'string', 'max:60'],
            'contact_passport_expiry' => ['required', 'date', 'after:today'],
            'country_of_residence' => ['required', 'string', 'max:100'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
            'dining_preference' => ['nullable', Rule::in(array_keys(config('cruise.dining_preferences', [])))],
            'bed_preference' => ['nullable', Rule::in(array_keys(config('cruise.bed_preferences', [])))],
            'celebration' => ['nullable', Rule::in(array_keys(config('cruise.celebrations', [])))],
            'wheelchair_assistance' => ['nullable', 'boolean'],
            'mobility_assistance' => ['nullable', 'boolean'],
            'special_needs' => ['nullable', 'boolean'],
            'dietary_requirements' => ['nullable', 'string', 'max:2000'],
            'medical_conditions' => ['nullable', 'string', 'max:2000'],
            'special_requests' => ['nullable', 'string', 'max:5000'],
            'emergency_contact_name' => ['required', 'string', 'max:255'],
            'emergency_contact_relationship' => ['required', 'string', 'max:100'],
            'emergency_contact_phone' => ['required', 'string', 'max:50'],
            'emergency_contact_email' => ['nullable', 'email', 'max:255'],
            'estimated_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],
            'privacy_accepted' => ['accepted'],
            'accept_conditions' => ['accepted'],
            'passengers' => ['nullable', 'array', 'max:15'],
            'passengers.*.title' => ['nullable', 'string', 'max:10'],
            'passengers.*.first_name' => ['required_with:passengers.*.last_name', 'string', 'max:100'],
            'passengers.*.last_name' => ['required_with:passengers.*.first_name', 'string', 'max:100'],
            'passengers.*.passenger_type' => ['nullable', Rule::in(['adult', 'child', 'infant'])],
            'passengers.*.gender' => ['nullable', 'string', 'max:20'],
            'passengers.*.date_of_birth' => ['nullable', 'date', 'before:today'],
            'passengers.*.nationality' => ['nullable', 'string', 'max:100'],
            'passengers.*.passport_number' => ['nullable', 'string', 'max:60'],
            'passengers.*.passport_expiry' => ['nullable', 'date', 'after:today'],
            'documents.passport' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'documents.visa' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'documents.insurance' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
            'documents.other' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }
}
