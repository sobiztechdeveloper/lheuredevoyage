<x-mail::message>
# New Holiday Package Request

**Reference:** {{ $request->reference_number }}  
**Status:** {{ $request->statusLabel() }}  
**Submitted:** {{ $request->created_at->format(config('date.display_datetime')) }}

## Travel Information

- **Departure Airport:** {{ $request->departure_airport ?: '—' }}
- **Destination:** {{ $request->destination }}
- **Earliest Departure:** {{ $request->earliest_departure_date?->format(config('date.display')) ?: '—' }}
- **Latest Return:** {{ $request->latest_return_date?->format(config('date.display')) ?: '—' }}
- **Duration:** {{ $request->duration ?: '—' }}
- **Adults:** {{ $request->adults }}
- **Children:** {{ $request->children }}
@if(!empty($request->child_ages))
- **Child Ages:** {{ implode(', ', $request->child_ages) }}
@endif

## Budget

- **Amount:** {{ $request->budget_amount ? number_format((float) $request->budget_amount, 2).' '.($request->budget_currency ?: '') : '—' }}

## Contact

- **Name:** {{ $request->full_name }}
- **Email:** {{ $request->email }}
- **Phone:** {{ $request->phone }}
- **Country:** {{ $request->country ?: '—' }}

@if($request->room_types || $request->board_types)
## Accommodation

@if($request->room_types)
- **Room Types:** {{ implode(', ', $request->translatedOptionList('room_types', $request->room_types)) }}
@endif
@if($request->board_types)
- **Board Types:** {{ implode(', ', $request->translatedOptionList('board_types', $request->board_types)) }}
@endif
@endif

@if($request->preferred_airline || $request->travel_class)
## Flight Preferences

- **Preferred Airline:** {{ $request->preferred_airline ?: '—' }}
- **Travel Class:** {{ $request->travel_class ? __('holiday_package_request.options.travel_classes.'.$request->travel_class) : '—' }}
- **Outbound Time:** {{ $request->outbound_time_preference ? __('holiday_package_request.options.time_preferences.'.$request->outbound_time_preference) : '—' }}
- **Return Time:** {{ $request->return_time_preference ? __('holiday_package_request.options.time_preferences.'.$request->return_time_preference) : '—' }}
- **Direct Flight Only:** {{ $request->direct_flight_only ? 'Yes' : 'No' }}
- **Transfer Allowed:** {{ $request->transfer_allowed ? 'Yes' : 'No' }}
- **Rail & Fly:** {{ $request->rail_and_fly ? 'Yes' : 'No' }}
@endif

@if($request->hotel_category || $request->hotel_features)
## Hotel Preferences

- **Category:** {{ $request->hotel_category ? __('holiday_package_request.options.hotel_categories.'.$request->hotel_category) : '—' }}
- **Recommendation:** {{ $request->hotel_recommendation ? $request->hotel_recommendation.'%' : '—' }}
- **Sea View:** {{ $request->sea_view ? __('holiday_package_request.options.sea_views.'.$request->sea_view) : '—' }}
@if($request->hotel_features)
- **Features:** {{ implode(', ', $request->translatedOptionList('hotel_features', $request->hotel_features)) }}
@endif
@endif

@if($request->beach_preferences)
- **Beach:** {{ implode(', ', $request->translatedOptionList('beach_preferences', $request->beach_preferences)) }}
@endif

@if($request->sports)
- **Sports:** {{ implode(', ', $request->translatedOptionList('sports', $request->sports)) }}
@endif

@if($request->wellness)
- **Wellness:** {{ implode(', ', $request->translatedOptionList('wellness', $request->wellness)) }}
@endif

@if($request->kids_club !== null || $request->babysitting !== null)
- **Kids Club:** {{ $request->kids_club ? 'Yes' : 'No' }}
- **Babysitting:** {{ $request->babysitting ? 'Yes' : 'No' }}
@endif

@if($request->room_amenities)
- **Room Amenities:** {{ implode(', ', $request->translatedOptionList('room_amenities', $request->room_amenities)) }}
@endif

@if($request->additional_notes)
## Additional Notes

{{ $request->additional_notes }}
@endif

<x-mail::button :url="route('admin.holiday-package-requests.show', $request)">
View in Admin Panel
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
