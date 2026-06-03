<?php

namespace App\Http\Requests;

use App\Models\FlightBookingRequest;
use App\Models\FlightSearchResult;
use App\Services\FlightBookingRequestService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreFlightBookingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'passengers' => ['required', 'array', 'min:1'],
            'passengers.*.passenger_type' => ['required', Rule::in(['adult', 'child', 'infant'])],
            'passengers.*.title' => ['required', 'string', 'max:10'],
            'passengers.*.first_name' => ['required', 'string', 'max:100'],
            'passengers.*.last_name' => ['required', 'string', 'max:100'],
            'passengers.*.date_of_birth' => ['required', 'date', 'before:today'],
            'passengers.*.gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'passengers.*.nationality' => ['required', 'string', 'max:100'],
            'passengers.*.passport_number' => ['required', 'string', 'max:50'],
            'passengers.*.passport_expiry' => ['required', 'date', 'after:today'],
            'passengers.*.passport_country' => ['nullable', 'string', 'max:100'],
            'passengers.*.passport_file' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
            'contact_passenger_index' => ['required', 'integer', 'min:0'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:100'],
            'preferred_airline' => ['nullable', 'string', 'max:255'],
            'seat_preference' => ['required', Rule::in(array_keys(FlightBookingRequest::SEAT_PREFERENCES))],
            'meal_preference' => ['required', Rule::in(array_keys(FlightBookingRequest::MEAL_PREFERENCES))],
            'special_assistance' => ['nullable', 'array'],
            'special_assistance.*' => ['nullable', 'boolean'],
            'special_requests' => ['nullable', 'string', 'max:2000'],
            'accept_conditions' => ['accepted'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            /** @var FlightSearchResult|null $result */
            $result = $this->route('flightSearchResult');
            if (! $result) {
                return;
            }

            $result->loadMissing('flightSearch');
            $search = $result->flightSearch;
            $service = app(FlightBookingRequestService::class);
            $expected = $service->passengerSlots(
                $search?->adult ?? 1,
                $search?->children ?? 0,
                $search?->infant ?? 0,
            );

            $passengers = $this->input('passengers', []);
            $minimumRequired = count($expected);

            if (count($passengers) < $minimumRequired) {
                $validator->errors()->add('passengers', 'Please complete details for all passengers from your search.');
            }

            if (count($passengers) > 9) {
                $validator->errors()->add('passengers', 'A maximum of 9 passengers can be added per request.');
            }

            $contactIndex = (int) $this->input('contact_passenger_index', -1);
            if (! array_key_exists($contactIndex, $passengers)) {
                $validator->errors()->add('contact_passenger_index', 'Please select a valid booking contact passenger.');
            }

            $typeCounts = ['adult' => 0, 'child' => 0, 'infant' => 0];
            foreach ($passengers as $passenger) {
                $type = $passenger['passenger_type'] ?? null;
                if (isset($typeCounts[$type])) {
                    $typeCounts[$type]++;
                }
            }

            if ($typeCounts['adult'] < ($search?->adult ?? 1)
                || $typeCounts['child'] < ($search?->children ?? 0)
                || $typeCounts['infant'] < ($search?->infant ?? 0)) {
                $validator->errors()->add('passengers', 'Include at least the number of adults, children, and infants from your search. You may add more passengers if needed.');
            }
        });
    }
}
