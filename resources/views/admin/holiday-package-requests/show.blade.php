@extends('layouts.admin.app')
@section('title', $request->reference_number)
@section('content')
<div class="admin-page-header">
    <div>
        <h1>{{ $request->reference_number }}</h1>
        <p class="text-muted small mb-0">{{ $request->full_name }} · {{ $request->email }} · <span class="badge bg-secondary text-uppercase">{{ $request->status }}</span></p>
    </div>
    <a href="{{ route('admin.holiday-package-requests.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="admin-form-card">
            <div class="admin-form-card-header"><h2>Travel Information</h2></div>
            <div class="admin-form-card-body">
                <div class="row g-3 small">
                    <div class="col-md-6"><span class="text-muted d-block">Departure Airport</span>{{ $request->departure_airport ?: '—' }}</div>
                    <div class="col-md-6"><span class="text-muted d-block">Destination</span>{{ $request->destination }}</div>
                    <div class="col-md-6"><span class="text-muted d-block">Earliest Departure</span>{{ $request->earliest_departure_date?->format(config('date.display')) ?: '—' }}</div>
                    <div class="col-md-6"><span class="text-muted d-block">Latest Return</span>{{ $request->latest_return_date?->format(config('date.display')) ?: '—' }}</div>
                    <div class="col-md-4"><span class="text-muted d-block">Duration</span>{{ $request->duration ?: '—' }}</div>
                    <div class="col-md-4"><span class="text-muted d-block">Adults</span>{{ $request->adults }}</div>
                    <div class="col-md-4"><span class="text-muted d-block">Children</span>{{ $request->children }}@if($request->child_ages) ({{ implode(', ', $request->child_ages) }})@endif</div>
                    <div class="col-md-6"><span class="text-muted d-block">Budget</span>{{ $request->budget_amount ? number_format((float) $request->budget_amount, 2).' '.($request->budget_currency ?: '') : '—' }}</div>
                    <div class="col-md-6"><span class="text-muted d-block">Locale</span>{{ strtoupper($request->locale) }}</div>
                </div>
            </div>
        </div>

        <div class="admin-form-card">
            <div class="admin-form-card-header"><h2>Request Metadata</h2></div>
            <div class="admin-form-card-body">
                <div class="row g-3 small">
                    <div class="col-md-6"><span class="text-muted d-block">Holiday Types</span>{{ $request->holidayTypeLabels() ? implode(', ', $request->holidayTypeLabels()) : '—' }}</div>
                    <div class="col-md-3"><span class="text-muted d-block">Priority</span>{{ $request->priorityLabel() }}</div>
                    <div class="col-md-3"><span class="text-muted d-block">Preferred Contact</span>{{ $request->preferredContactMethodLabel() }}</div>
                    <div class="col-md-6"><span class="text-muted d-block">GDPR Consent</span>{{ $request->gdpr_consent_at?->format(config('date.display_datetime')) ?: '—' }}</div>
                </div>
            </div>
        </div>

        <div class="admin-form-card">
            <div class="admin-form-card-header"><h2>Contact</h2></div>
            <div class="admin-form-card-body small">
                <p class="mb-1"><strong>{{ $request->full_name }}</strong></p>
                <p class="mb-1">{{ $request->email }} · {{ $request->phone }}</p>
                <p class="mb-0">{{ $request->country ?: '—' }}</p>
            </div>
        </div>

        @php
            $listSections = [
                ['title' => 'Accommodation — Room Types', 'values' => $request->room_types, 'group' => 'room_types'],
                ['title' => 'Accommodation — Board Types', 'values' => $request->board_types, 'group' => 'board_types'],
                ['title' => 'Hotel Features', 'values' => $request->hotel_features, 'group' => 'hotel_features'],
                ['title' => 'Beach Preferences', 'values' => $request->beach_preferences, 'group' => 'beach_preferences'],
                ['title' => 'Sports', 'values' => $request->sports, 'group' => 'sports'],
                ['title' => 'Wellness', 'values' => $request->wellness, 'group' => 'wellness'],
                ['title' => 'Room Amenities', 'values' => $request->room_amenities, 'group' => 'room_amenities'],
            ];
        @endphp

        <div class="admin-form-card">
            <div class="admin-form-card-header"><h2>Preferences</h2></div>
            <div class="admin-form-card-body small">
                <div class="row g-3">
                    <div class="col-md-6"><span class="text-muted d-block">Preferred Airline</span>{{ ($config['option_labels']['preferred_airlines'][$request->preferred_airline] ?? $request->preferred_airline) ?: '—' }}</div>
                    <div class="col-md-6"><span class="text-muted d-block">Travel Class</span>{{ ($config['option_labels']['travel_classes'][$request->travel_class] ?? ($request->travel_class ? __('holiday_package_request.options.travel_classes.'.$request->travel_class) : null)) ?: '—' }}</div>
                    <div class="col-md-6"><span class="text-muted d-block">Outbound Time</span>{{ $request->outbound_time_preference ? __('holiday_package_request.options.time_preferences.'.$request->outbound_time_preference) : '—' }}</div>
                    <div class="col-md-6"><span class="text-muted d-block">Return Time</span>{{ $request->return_time_preference ? __('holiday_package_request.options.time_preferences.'.$request->return_time_preference) : '—' }}</div>
                    <div class="col-md-4"><span class="text-muted d-block">Direct Flight Only</span>{{ $request->direct_flight_only ? 'Yes' : 'No' }}</div>
                    <div class="col-md-4"><span class="text-muted d-block">Connecting Flight</span>{{ $request->transfer_allowed ? 'Yes' : 'No' }}</div>
                    <div class="col-md-4"><span class="text-muted d-block">Rail & Fly</span>{{ $request->rail_and_fly ? 'Yes' : 'No' }}</div>
                    <div class="col-md-4"><span class="text-muted d-block">Hotel Category</span>{{ $request->hotel_category ? __('holiday_package_request.options.hotel_categories.'.$request->hotel_category) : '—' }}</div>
                    <div class="col-md-4"><span class="text-muted d-block">Recommendation</span>{{ $request->hotel_recommendation ? $request->hotel_recommendation.'%' : '—' }}</div>
                    <div class="col-md-4"><span class="text-muted d-block">Sea View</span>{{ $request->sea_view ? __('holiday_package_request.options.sea_views.'.$request->sea_view) : '—' }}</div>
                    <div class="col-md-6"><span class="text-muted d-block">Kids Club</span>{{ $request->kids_club === null ? '—' : ($request->kids_club ? 'Yes' : 'No') }}</div>
                    <div class="col-md-6"><span class="text-muted d-block">Babysitting</span>{{ $request->babysitting === null ? '—' : ($request->babysitting ? 'Yes' : 'No') }}</div>
                </div>
                @foreach($listSections as $section)
                    @if(!empty($section['values']))
                        <p class="mt-3 mb-1 fw-semibold">{{ $section['title'] }}</p>
                        <p class="mb-0">{{ implode(', ', $request->translatedOptionList($section['group'], $section['values'])) }}</p>
                    @endif
                @endforeach
            </div>
        </div>

        @if($request->additional_notes)
        <div class="admin-form-card">
            <div class="admin-form-card-header"><h2>Additional Notes</h2></div>
            <div class="admin-form-card-body"><p class="mb-0 small">{{ $request->additional_notes }}</p></div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <div class="admin-form-card">
            <div class="admin-form-card-header"><h2>Status</h2></div>
            <div class="admin-form-card-body">
                <form method="POST" action="{{ route('admin.holiday-package-requests.update', $request) }}">
                    @csrf @method('PUT')
                    <div class="mb-3">
                        <label class="admin-field-label">Status</label>
                        <select name="status" class="form-select" required>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" @selected($request->status === $status)>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="admin-field-label">Agent Notes</label>
                        <textarea name="agent_notes" class="form-control" rows="5">{{ old('agent_notes', $request->agent_notes) }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-admin-primary w-100">Update Request</button>
                </form>
            </div>
        </div>
        <div class="admin-form-card">
            <div class="admin-form-card-body small text-muted">
                <p class="mb-1"><strong>Created:</strong> {{ $request->created_at->format(config('date.display_datetime')) }}</p>
                <p class="mb-0"><strong>Updated:</strong> {{ $request->updated_at->format(config('date.display_datetime')) }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
