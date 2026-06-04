<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreInsuranceQuoteRequest extends FormRequest
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
            'destination_country' => ['required', 'string', 'max:120'],
            'purpose_of_travel' => ['required', Rule::in(array_keys(config('insurance.travel_purposes', [])))],
            'travel_start' => ['required', 'date', 'after_or_equal:today'],
            'travel_end' => ['required', 'date', 'after:travel_start'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:5000'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'pre_existing_conditions' => ['nullable', 'boolean'],
            'pregnancy' => ['nullable', 'boolean'],
            'adventure_sports' => ['nullable', 'boolean'],
            'winter_sports' => ['nullable', 'boolean'],
            'long_stay' => ['nullable', 'boolean'],
            'additional_notes' => ['nullable', 'string', 'max:5000'],
            'estimated_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],
            'privacy_accepted' => ['accepted'],
            'accept_conditions' => ['accepted'],

            'travelers' => ['required', 'array', 'min:1', 'max:15'],
            'travelers.*.title' => ['nullable', 'string', 'max:10'],
            'travelers.*.first_name' => ['required', 'string', 'max:100'],
            'travelers.*.last_name' => ['required', 'string', 'max:100'],
            'travelers.*.date_of_birth' => ['required', 'date', 'before:today'],
            'travelers.*.nationality' => ['required', 'string', 'max:100'],
            'travelers.*.passport_number' => ['nullable', 'string', 'max:60'],
            'travelers.*.passport_expiry' => ['nullable', 'date', 'after:today'],
            'travelers.*.relationship' => ['nullable', 'string', 'max:60'],
            'travelers.*.passport_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],

            'customer_documents.passport' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'customer_documents.visa' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'customer_documents.student_letter' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'customer_documents.medical' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ];
    }
}
