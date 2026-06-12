<?php

namespace App\Http\Requests;

use App\Services\HolidayPackageRequestConfigService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreHolidayPackageRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $merge = [
            'direct_flight_only' => $this->boolean('direct_flight_only'),
            'transfer_allowed' => $this->boolean('transfer_allowed'),
            'rail_and_fly' => $this->boolean('rail_and_fly'),
            'kids_club' => $this->has('kids_club') ? $this->boolean('kids_club') : null,
            'babysitting' => $this->has('babysitting') ? $this->boolean('babysitting') : null,
            'gdpr_consent' => $this->boolean('gdpr_consent'),
        ];

        foreach (['earliest_departure_date', 'latest_return_date'] as $dateField) {
            if ($this->filled($dateField)) {
                $normalized = normalize_user_date($this->input($dateField));

                if ($normalized !== null) {
                    $merge[$dateField] = $normalized;
                }
            }
        }

        $this->merge($merge);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $options = app(HolidayPackageRequestConfigService::class);
        $static = config('holiday_package_request', []);

        $prioritySlugs = $options->activeSlugs('priorities');
        $contactSlugs = $options->activeSlugs('contact_methods');
        $airlineSlugs = $options->activeSlugs('preferred_airlines');

        return [
            'locale' => ['nullable', 'string', 'in:en,fr,nl'],
            'departure_airport' => ['nullable', 'string', 'max:255'],
            'destination' => ['required', 'string', 'max:255'],
            'earliest_departure_date' => ['nullable', 'date'],
            'latest_return_date' => ['nullable', 'date', 'after_or_equal:earliest_departure_date'],
            'duration' => ['nullable', 'string', 'max:120'],
            'adults' => ['required', 'integer', 'min:1', 'max:20'],
            'children' => ['nullable', 'integer', 'min:0', 'max:10'],
            'child_ages' => ['nullable', 'array', 'max:10'],
            'child_ages.*' => ['nullable', 'integer', 'min:0', 'max:17'],
            'holiday_types' => ['nullable', 'array'],
            'holiday_types.*' => ['string', Rule::in($options->activeSlugs('holiday_types'))],
            'priority' => $options->hasActivePriorities()
                ? ['required', 'string', Rule::in($prioritySlugs)]
                : ['nullable'],
            'preferred_contact_method' => $options->hasActiveContactMethods()
                ? ['required', 'string', Rule::in($contactSlugs)]
                : ['nullable'],
            'gdpr_consent' => ['required', 'accepted'],
            'budget_amount' => ['nullable', 'numeric', 'min:0'],
            'budget_currency' => ['nullable', 'string', Rule::in($static['budget_currencies'] ?? [])],
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'country' => ['nullable', 'string', 'max:120'],
            'room_types' => ['nullable', 'array'],
            'room_types.*' => ['string', Rule::in($options->activeSlugs('room_types'))],
            'board_types' => ['nullable', 'array'],
            'board_types.*' => ['string', Rule::in($options->activeSlugs('board_types'))],
            'preferred_airline' => $options->hasActiveAirlines()
                ? ['nullable', 'string', 'max:120', Rule::in($airlineSlugs)]
                : ['nullable', 'string', 'max:120'],
            'travel_class' => ['nullable', 'string', Rule::in($options->activeSlugs('travel_classes'))],
            'outbound_time_preference' => ['nullable', 'string', Rule::in($options->activeSlugs('time_preferences'))],
            'return_time_preference' => ['nullable', 'string', Rule::in($options->activeSlugs('time_preferences'))],
            'direct_flight_only' => ['nullable', 'boolean'],
            'transfer_allowed' => ['nullable', 'boolean'],
            'rail_and_fly' => ['nullable', 'boolean'],
            'hotel_category' => ['nullable', 'string', Rule::in($options->activeSlugs('hotel_categories'))],
            'hotel_recommendation' => ['nullable', 'string', Rule::in($static['hotel_recommendations'] ?? [])],
            'sea_view' => ['nullable', 'string', Rule::in($options->activeSlugs('sea_views'))],
            'hotel_features' => ['nullable', 'array'],
            'hotel_features.*' => ['string', Rule::in($options->activeSlugs('hotel_features'))],
            'beach_preferences' => ['nullable', 'array'],
            'beach_preferences.*' => ['string', Rule::in($options->activeSlugs('beach_preferences'))],
            'sports' => ['nullable', 'array'],
            'sports.*' => ['string', Rule::in($options->activeSlugs('sports'))],
            'wellness' => ['nullable', 'array'],
            'wellness.*' => ['string', Rule::in($options->activeSlugs('wellness'))],
            'kids_club' => ['nullable', 'boolean'],
            'babysitting' => ['nullable', 'boolean'],
            'room_amenities' => ['nullable', 'array'],
            'room_amenities.*' => ['string', Rule::in($options->activeSlugs('room_amenities'))],
            'additional_notes' => ['nullable', 'string', 'max:5000'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $options = app(HolidayPackageRequestConfigService::class);

        $validator->after(function (Validator $validator) use ($options): void {
            if (! $options->hasActivePriorities()) {
                $validator->errors()->add('priority', 'No active request priorities configured.');
            }

            if (! $options->hasActiveContactMethods()) {
                $validator->errors()->add('preferred_contact_method', 'No active contact methods configured.');
            }

            if (! $options->hasActiveAirlines()) {
                $validator->errors()->add('preferred_airline', 'No airlines configured.');
            }
        });
    }
}
