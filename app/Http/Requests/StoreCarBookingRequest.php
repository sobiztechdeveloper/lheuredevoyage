<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCarBookingRequest extends FormRequest
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
            'rental_car_id' => ['required', 'exists:rental_cars,id'],
            'pickup_location' => ['required', 'string', 'max:255'],
            'dropoff_location' => ['nullable', 'string', 'max:255'],
            'pickup_date' => ['required', 'date', 'after_or_equal:today'],
            'pickup_time' => ['nullable', 'string', 'max:50'],
            'return_date' => ['required', 'date', 'after:pickup_date'],
            'return_time' => ['nullable', 'string', 'max:50'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['required', 'string', 'max:50'],
            'contact_whatsapp' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:5000'],
            'country' => ['nullable', 'string', 'max:100'],
            'extra_gps' => ['nullable', 'boolean'],
            'extra_child_seat' => ['nullable', 'boolean'],
            'extra_additional_driver' => ['nullable', 'boolean'],
            'insurance_option' => ['nullable', 'string', 'max:120'],
            'notes' => ['nullable', 'string', 'max:5000'],
            'estimated_amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],

            'drivers' => ['required', 'array', 'min:1', 'max:10'],
            'drivers.*.title' => ['nullable', 'string', 'max:10'],
            'drivers.*.first_name' => ['required', 'string', 'max:100'],
            'drivers.*.last_name' => ['required', 'string', 'max:100'],
            'drivers.*.date_of_birth' => ['nullable', 'date', 'before:today'],
            'drivers.*.nationality' => ['nullable', 'string', 'max:100'],
            'drivers.*.license_number' => ['nullable', 'string', 'max:80'],
            'drivers.*.license_expiry' => ['nullable', 'date'],
            'drivers.*.passport_number' => ['nullable', 'string', 'max:60'],
            'drivers.*.license_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'drivers.*.passport_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'accept_conditions' => ['accepted'],
        ];
    }
}
