@extends('layouts.app')
@section('body-class', 'home-3 fbw-page')

@section('content')
@php
    $occupancyLabel = $context['adults'].' adult(s)';
    if ($context['children']) {
        $occupancyLabel .= ', '.$context['children'].' child(ren)';
    }
    if ($context['infants']) {
        $occupancyLabel .= ', '.$context['infants'].' infant(s)';
    }
@endphp

<x-booking-wizard-layout
    title="Hotel Booking Request"
    :breadcrumbs="[
        ['label' => $hotel->name, 'url' => route('hotel.show', $hotel)],
        ['label' => 'Booking Request'],
    ]"
    :steps="['Summary', 'Your Details', 'Review']"
    :form-action="route('hotel.booking.store')"
>
    <x-slot:sidebar>
        @include('partials.booking.wizard-summary', ['summary' => $summary])
    </x-slot:sidebar>

    @csrf
    <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
    <input type="hidden" name="room_id" value="{{ $room?->id }}">
    <input type="hidden" name="check_in_date" value="{{ $context['check_in']->format('Y-m-d') }}">
    <input type="hidden" name="check_out_date" value="{{ $context['check_out']->format('Y-m-d') }}">
    <input type="hidden" name="rooms" value="{{ $context['rooms'] }}">
    <input type="hidden" name="adults" value="{{ $context['adults'] }}">
    <input type="hidden" name="children" value="{{ $context['children'] }}">
    <input type="hidden" name="infants" value="{{ $context['infants'] }}">
    <input type="hidden" name="estimated_amount" value="{{ $context['estimated_amount'] }}">
    <input type="hidden" name="currency" value="{{ $context['currency'] }}">

    <div class="fbw-step-panel active" data-step="1">
        <div class="fbw-card">
            <h4 class="fbw-card-title">Your Selection</h4>
            <p class="mb-2"><strong>{{ $hotel->name }}</strong>@if($room) — {{ $room->name }}@endif</p>
            <p class="text-muted mb-2">{{ $context['check_in']->format(config('date.display')) }} → {{ $context['check_out']->format(config('date.display')) }} ({{ $context['nights'] }} nights)</p>
            <p class="text-muted mb-3">{{ $context['rooms'] }} room(s) · {{ $occupancyLabel }}</p>
            <div class="fbw-notice mb-0">
                This is a <strong>booking request only</strong>. Our team will confirm availability and final pricing. No payment required now.
            </div>
            <div class="fbw-actions">
                <a href="{{ route('hotel.show', $hotel) }}" class="fbw-btn-outline">Back to Hotel</a>
                <button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button>
            </div>
        </div>
    </div>

    <div class="fbw-step-panel" data-step="2">
        <div class="fbw-card">
            <h4 class="fbw-card-title">Your Details</h4>
            <p class="text-muted small mb-4">Enter the booking contact details. Guest names for additional travelers can be confirmed with our consultant.</p>

            <div class="mb-4">
                <label class="form-label fw-semibold">Who are you booking for?</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="booking_for" id="bf-main" value="main_guest" @checked(old('booking_for', 'main_guest') === 'main_guest')>
                    <label class="form-check-label" for="bf-main">I'm the main guest</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="booking_for" id="bf-other" value="someone_else" @checked(old('booking_for') === 'someone_else')>
                    <label class="form-check-label" for="bf-other">I'm booking for someone else</label>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-2">
                    <label class="form-label">Title</label>
                    <select name="lead_guest_title" class="form-select">
                        @foreach(['Mr','Mrs','Ms'] as $t)
                        <option value="{{ $t }}" @selected(old('lead_guest_title') === $t)>{{ $t }}</option>
                        @endforeach
                    </select>
                </div>
                @php
                    $userName = auth()->user()?->name ?? '';
                    $nameParts = $userName !== '' ? preg_split('/\s+/', trim($userName), 2) : [];
                @endphp
                <div class="col-md-5">
                    <label class="form-label">First name <span class="text-danger">*</span></label>
                    <input type="text" name="lead_guest_first_name" class="form-control" required value="{{ old('lead_guest_first_name', $nameParts[0] ?? '') }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label">Last name <span class="text-danger">*</span></label>
                    <input type="text" name="lead_guest_last_name" class="form-control" required value="{{ old('lead_guest_last_name', $nameParts[1] ?? '') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="lead_guest_email" class="form-control" required value="{{ old('lead_guest_email', auth()->user()?->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone <span class="text-danger">*</span></label>
                    <input type="tel" name="lead_guest_phone" class="form-control" required value="{{ old('lead_guest_phone') }}">
                </div>
                <div class="col-md-6"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ old('country') }}"></div>
                <div class="col-md-6"><label class="form-label">WhatsApp</label><input type="tel" name="lead_guest_whatsapp" class="form-control" value="{{ old('lead_guest_whatsapp') }}"></div>
            </div>

            <h5 class="fbw-card-title" style="border:none;padding:0;">Room & arrival</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Bed preference</label>
                    <select name="bed_preference" class="form-select">
                        @foreach($bedPreferences as $k => $l)
                        <option value="{{ $k }}" @selected(old('bed_preference') === $k)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Smoking preference</label>
                    <select name="smoking_preference" class="form-select">
                        @foreach($smokingPreferences as $k => $l)
                        <option value="{{ $k }}" @selected(old('smoking_preference') === $k)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Arrival time</label>
                    <select name="arrival_time" class="form-select">
                        <option value="">I don't know</option>
                        @foreach($arrivalTimes as $k => $l)
                        <option value="{{ $k }}" @selected(old('arrival_time') === $k)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <h5 class="fbw-card-title" style="border:none;padding:0;">Special requests</h5>
            <div class="row g-2 mb-3">
                @foreach($specialOptions as $key => $label)
                <div class="col-md-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="special_request_options[{{ $key }}]" value="1" id="sr-{{ $key }}" @checked(old("special_request_options.{$key}"))>
                        <label class="form-check-label" for="sr-{{ $key }}">{{ $label }}</label>
                    </div>
                </div>
                @endforeach
            </div>
            <label class="form-label">Message to the property</label>
            <textarea name="special_requests" class="form-control" rows="3">{{ old('special_requests') }}</textarea>

            <div class="fbw-actions mt-4">
                <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                <button type="button" class="fbw-btn-primary" data-fbw-next>Review</button>
            </div>
        </div>
    </div>

    <div class="fbw-step-panel" data-step="3">
        <div class="fbw-card">
            <h4 class="fbw-card-title">Review Your Request</h4>
            <div class="fbw-review-section">
                <h6>Stay summary</h6>
                <div id="fbw-review-content"></div>
            </div>
            <div class="fbw-notice">
                <strong>This is a booking request only.</strong> Availability and final pricing will be confirmed by our travel consultant.
            </div>
            <x-legal-booking-consent id="accept" />
            <div class="fbw-actions">
                <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                <button type="submit" class="fbw-btn-primary">Submit Booking Request</button>
            </div>
        </div>
    </div>
</x-booking-wizard-layout>
@endsection

@push('scripts')
<script>
window.hbwSummary = {
    hotel: @json($hotel->name),
    room: @json($room?->name ?? 'Room'),
    location: @json($hotel->location),
    check_in: @json($context['check_in']->format(config('date.display'))),
    check_out: @json($context['check_out']->format(config('date.display'))),
    estimated: @json(strtoupper($context['currency']).' '.number_format($context['estimated_amount'], 0))
};
</script>
<script src="{{ asset('assets/js/booking-request-wizard.js') }}?v={{ filemtime(public_path('assets/js/booking-request-wizard.js')) }}"></script>
@endpush
