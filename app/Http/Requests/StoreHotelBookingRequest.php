<?php

namespace App\Http\Requests;

use App\Models\HotelBookingRequest;
use App\Models\HotelRoom;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreHotelBookingRequest extends FormRequest
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
            'hotel_id' => ['required', 'exists:hotels,id'],
            'room_id' => [
                'nullable',
                Rule::exists('hotel_rooms', 'id')->where(fn ($q) => $q->where('hotel_id', $this->input('hotel_id'))->where('is_active', true)),
            ],
            'check_in_date' => ['required', 'date', 'after_or_equal:today'],
            'check_out_date' => ['required', 'date', 'after:check_in_date'],
            'rooms' => ['required', 'integer', 'min:1', 'max:10'],
            'adults' => ['required', 'integer', 'min:1', 'max:20'],
            'children' => ['nullable', 'integer', 'min:0', 'max:20'],
            'infants' => ['nullable', 'integer', 'min:0', 'max:10'],
            'estimated_amount' => ['required', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:3'],

            'lead_guest_title' => ['nullable', 'string', 'max:10'],
            'lead_guest_first_name' => ['required', 'string', 'max:100'],
            'lead_guest_last_name' => ['required', 'string', 'max:100'],
            'lead_guest_email' => ['required', 'email', 'max:255'],
            'lead_guest_phone' => ['required', 'string', 'max:50'],
            'lead_guest_whatsapp' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'booking_for' => ['nullable', 'in:main_guest,someone_else'],

            'bed_preference' => ['nullable', 'in:'.implode(',', array_keys(HotelBookingRequest::BED_PREFERENCES))],
            'smoking_preference' => ['nullable', 'in:'.implode(',', array_keys(HotelBookingRequest::SMOKING_PREFERENCES))],
            'arrival_time' => ['nullable', 'string', 'max:30'],
            'special_request_options' => ['nullable', 'array'],
            'special_requests' => ['nullable', 'string', 'max:5000'],
            'accept_conditions' => ['accepted'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            if ($this->filled('room_id') && $this->filled('hotel_id')) {
                $valid = HotelRoom::query()
                    ->where('hotel_id', $this->input('hotel_id'))
                    ->where('id', $this->input('room_id'))
                    ->where('is_active', true)
                    ->exists();

                if (! $valid) {
                    $validator->errors()->add('room_id', 'The selected room is not available for this hotel.');
                }
            }
        });
    }
}
