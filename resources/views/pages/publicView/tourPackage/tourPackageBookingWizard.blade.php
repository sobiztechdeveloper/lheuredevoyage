@extends('layouts.app')
@section('body-class', 'home-3 fbw-page')

@section('content')
@php
    $occupancyLabel = $travelers['adult'].' adult(s)';
    if ($travelers['children']) {
        $occupancyLabel .= ', '.$travelers['children'].' child(ren)';
    }
    if ($travelers['infant']) {
        $occupancyLabel .= ', '.$travelers['infant'].' infant(s)';
    }
@endphp

<x-booking-wizard-layout
    title="Holiday Package Booking Request"
    :breadcrumbs="[
        ['label' => $item->title, 'url' => route('tourpackage.show', $item)],
        ['label' => 'Booking Request'],
    ]"
    :steps="['Package', 'Your Details', 'Review']"
    :form-action="route('bookings.store')"
>
    <x-slot:sidebar>
        @include('partials.booking.wizard-summary', ['summary' => $summary])
    </x-slot:sidebar>

    @csrf
    <input type="hidden" name="bookable_type" value="{{ $bookableType }}">
    <input type="hidden" name="bookable_slug" value="{{ $item->slug }}">

    <div class="fbw-step-panel active" data-step="1">
        <div class="fbw-card">
            <h4 class="fbw-card-title">Your Selected Package</h4>
            <p class="mb-2"><strong>{{ $item->title }}</strong></p>
            @if($item->displayCountry())
                <p class="text-muted mb-2"><i class="far fa-location-dot me-1"></i>{{ $item->displayCountry() }}</p>
            @endif
            @if($item->displayDuration())
                <p class="text-muted mb-2"><i class="far fa-clock me-1"></i>{{ $item->displayDuration() }}</p>
            @endif
            <p class="text-muted mb-3"><i class="far fa-user me-1"></i>{{ $occupancyLabel }}</p>
            <div class="fbw-notice mb-0">
                This is a <strong>booking request only</strong>. Our travel consultant will confirm availability and final pricing. No payment required now.
            </div>
            <div class="fbw-actions">
                <a href="{{ route('tourpackage.show', $item) }}" class="fbw-btn-outline">Back to Package</a>
                <button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button>
            </div>
        </div>
    </div>

    <div class="fbw-step-panel" data-step="2">
        <div class="fbw-card">
            <h4 class="fbw-card-title">Your Details</h4>
            <p class="text-muted small mb-4">Enter your contact details and travel preferences. Additional traveler names can be confirmed with our consultant.</p>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="guest_name" class="form-control" required value="{{ old('guest_name', auth()->user()?->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="guest_email" class="form-control" required value="{{ old('guest_email', auth()->user()?->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="guest_phone" class="form-control" value="{{ old('guest_phone', auth()->user()?->profile?->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Preferred Travel Date</label>
                    <input type="date" name="travel_date" class="form-control" value="{{ old('travel_date') }}">
                </div>
            </div>

            <x-catalog-traveler-fields
                class="mb-4"
                :adult="$travelers['adult']"
                :children="$travelers['children']"
                :infant="$travelers['infant']"
            />

            <div class="mb-0">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="Special requests, dietary needs, or questions">{{ old('notes') }}</textarea>
            </div>

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
                <h6>Package summary</h6>
                <div id="fbw-review-content"></div>
            </div>
            <div class="fbw-notice">
                <strong>This is a booking request only.</strong> Availability and final pricing will be confirmed by our travel consultant.
            </div>
            @auth
                <x-legal-booking-consent id="tourpackage-accept" />
                <div class="fbw-actions">
                    <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                    <button type="submit" class="fbw-btn-primary">Submit Booking Request</button>
                </div>
            @else
                <p class="text-muted mb-0">Please <a href="{{ route('login') }}">sign in</a> to submit your booking request.</p>
                <div class="fbw-actions">
                    <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                    <a href="{{ route('login') }}" class="fbw-btn-primary">Sign in to Submit</a>
                </div>
            @endauth
        </div>
    </div>
</x-booking-wizard-layout>
@endsection

@push('scripts')
<script>
window.bookingWizardReview = {
    headline: @json($item->title),
    subtitle: @json($item->displayCountry()),
    fare: @json($item->formatted_price.' / '.$item->price_unit),
    sections: [
        {
            title: 'Package',
            html: {!! json_encode(
                '<p><strong>'.e($item->title).'</strong></p>'
                .($item->displayCountry() ? '<p class="text-muted mb-2">'.e($item->displayCountry()).'</p>' : '')
                .($item->displayDuration() ? '<p class="text-muted mb-2">'.e($item->displayDuration()).'</p>' : '')
                .'<p class="mb-0"><strong>From:</strong> '.e($item->formatted_price).' / '.e($item->price_unit).'</p>'
            ) !!}
        },
        {
            title: 'Travelers',
            html: '<p class="mb-0" id="tpw-review-travelers">{{ $occupancyLabel }}</p>'
        },
        {
            title: 'Contact',
            html: '<p class="mb-1"><strong>Name:</strong> <span data-review="guest_name">—</span></p>'
                + '<p class="mb-1"><strong>Email:</strong> <span data-review="guest_email">—</span></p>'
                + '<p class="mb-1"><strong>Phone:</strong> <span data-review="guest_phone">—</span></p>'
                + '<p class="mb-0"><strong>Travel date:</strong> <span data-review="travel_date">—</span></p>'
        }
    ]
};

(function () {
    var form = document.getElementById('fbw-form');
    if (!form) return;

    function syncReview() {
        ['guest_name', 'guest_email', 'guest_phone', 'travel_date'].forEach(function (name) {
            var field = form.querySelector('[name="' + name + '"]');
            var target = form.querySelector('[data-review="' + name + '"]');
            if (field && target) {
                target.textContent = field.value || '—';
            }
        });

        var adult = parseInt(form.querySelector('[name="adult"]')?.value, 10) || 0;
        var children = parseInt(form.querySelector('[name="children"]')?.value, 10) || 0;
        var infant = parseInt(form.querySelector('[name="infant"]')?.value, 10) || 0;
        var parts = [];
        if (adult > 0) parts.push(adult + (adult === 1 ? ' adult' : ' adults'));
        if (children > 0) parts.push(children + (children === 1 ? ' child' : ' children'));
        if (infant > 0) parts.push(infant + (infant === 1 ? ' infant' : ' infants'));
        var travelersEl = document.getElementById('tpw-review-travelers');
        if (travelersEl) {
            travelersEl.textContent = parts.length ? parts.join(', ') : '—';
        }
    }

    form.addEventListener('input', syncReview);
    syncReview();
})();
</script>
<script src="{{ asset('assets/js/booking-request-wizard.js') }}?v={{ filemtime(public_path('assets/js/booking-request-wizard.js')) }}"></script>
@endpush
