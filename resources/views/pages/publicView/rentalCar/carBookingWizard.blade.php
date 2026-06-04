@extends('layouts.app')
@section('body-class', 'home-3 fbw-page')

@section('content')
@php
    $summary = \App\Support\BookingWizardSummary::forCar($rentalCar, $context);
@endphp

<x-booking-wizard-layout
    title="Car Rental Request"
    :breadcrumbs="[
        ['label' => $rentalCar->name, 'url' => route('rentalcar.show', $rentalCar)],
        ['label' => 'Booking Request'],
    ]"
    :steps="['Summary', 'Trip & Contact', 'Drivers', 'Review']"
    :form-action="route('rentalcar.booking.store')"
    form-enctype="multipart/form-data"
>
    <x-slot:sidebar>
        @include('partials.booking.wizard-summary', ['summary' => $summary])
    </x-slot:sidebar>

    @csrf
    <input type="hidden" name="rental_car_id" value="{{ $rentalCar->id }}">
    <input type="hidden" name="estimated_amount" value="{{ $context['estimated_amount'] }}">
    <input type="hidden" name="currency" value="{{ $context['currency'] }}">

    <div class="fbw-step-panel active" data-step="1">
        <div class="fbw-card">
            <h4 class="fbw-card-title">Your Selected Vehicle</h4>
            <p class="text-muted mb-3">Review your rental before entering trip and driver details.</p>
            <div class="fbw-notice mb-0">
                This is a <strong>booking request only</strong>. Our consultant will confirm availability and final pricing.
            </div>
            <div class="fbw-actions">
                <a href="{{ route('rentalcar.show', $rentalCar) }}" class="fbw-btn-outline">Back to Vehicle</a>
                <button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button>
            </div>
        </div>
    </div>

    <div class="fbw-step-panel" data-step="2">
        <div class="fbw-card">
            <h4 class="fbw-card-title">Trip & Contact</h4>
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Pickup Location <span class="text-danger">*</span></label><input type="text" name="pickup_location" class="form-control" required value="{{ old('pickup_location') }}"></div>
                <div class="col-md-6"><label class="form-label">Dropoff Location</label><input type="text" name="dropoff_location" class="form-control" value="{{ old('dropoff_location') }}"></div>
                <div class="col-md-3"><label class="form-label">Pickup Date <span class="text-danger">*</span></label><input type="date" name="pickup_date" class="form-control" required value="{{ old('pickup_date', $context['pickup_date']->format('Y-m-d')) }}"></div>
                <div class="col-md-3"><label class="form-label">Pickup Time</label><input type="text" name="pickup_time" class="form-control" value="{{ old('pickup_time') }}" placeholder="10:00"></div>
                <div class="col-md-3"><label class="form-label">Return Date <span class="text-danger">*</span></label><input type="date" name="return_date" class="form-control" required value="{{ old('return_date', $context['return_date']->format('Y-m-d')) }}"></div>
                <div class="col-md-3"><label class="form-label">Return Time</label><input type="text" name="return_time" class="form-control" value="{{ old('return_time') }}" placeholder="18:00"></div>
                <div class="col-md-6"><label class="form-label">Email <span class="text-danger">*</span></label><input type="email" name="contact_email" class="form-control" required value="{{ old('contact_email', auth()->user()?->email) }}"></div>
                <div class="col-md-6"><label class="form-label">Phone <span class="text-danger">*</span></label><input type="text" name="contact_phone" class="form-control" required value="{{ old('contact_phone') }}"></div>
                <div class="col-md-6"><label class="form-label">WhatsApp</label><input type="text" name="contact_whatsapp" class="form-control" value="{{ old('contact_whatsapp') }}"></div>
                <div class="col-md-6"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ old('country') }}"></div>
                <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea></div>
            </div>
            <div class="fbw-actions mt-4">
                <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                <button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button>
            </div>
        </div>
    </div>

    <div class="fbw-step-panel" data-step="3">
        <div class="fbw-card">
            <h4 class="fbw-card-title">Drivers & Extras</h4>
            @include('pages.publicView.rentalCar.partials.car-booking-driver-fields')
            <div class="row g-3 mt-2">
                <div class="col-md-4"><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="extra_gps" value="1" id="extra_gps" @checked(old('extra_gps'))><label class="form-check-label" for="extra_gps">GPS</label></div></div>
                <div class="col-md-4"><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="extra_child_seat" value="1" id="extra_child_seat" @checked(old('extra_child_seat'))><label class="form-check-label" for="extra_child_seat">Child seat</label></div></div>
                <div class="col-md-4"><div class="form-check mt-2"><input class="form-check-input" type="checkbox" name="extra_additional_driver" value="1" id="extra_additional_driver" @checked(old('extra_additional_driver'))><label class="form-check-label" for="extra_additional_driver">Additional driver</label></div></div>
                <div class="col-md-6"><label class="form-label">Insurance option</label><input type="text" name="insurance_option" class="form-control" value="{{ old('insurance_option') }}"></div>
                <div class="col-12"><label class="form-label">Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea></div>
            </div>
            <div class="fbw-actions mt-4">
                <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                <button type="button" class="fbw-btn-primary" data-fbw-next>Review</button>
            </div>
        </div>
    </div>

    <div class="fbw-step-panel" data-step="4">
        <div class="fbw-card">
            <h4 class="fbw-card-title">Review Your Request</h4>
            <div class="fbw-review-section">
                <h6>Booking summary</h6>
                <div id="fbw-review-content"></div>
            </div>
            <div class="fbw-notice">
                <strong>This is a booking request only.</strong> Availability and final fare will be confirmed by our travel consultant. No payment is required now.
            </div>
            <x-legal-booking-consent id="car-accept" />
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
window.catalogBookingWizardSummary = {
    product: @json($rentalCar->name),
    location: @json($rentalCar->location),
    estimated: @json(strtoupper($context['currency']).' '.number_format($context['estimated_amount'], 0)),
    type: 'car'
};
</script>
<script src="{{ asset('assets/js/booking-request-wizard.js') }}?v={{ filemtime(public_path('assets/js/booking-request-wizard.js')) }}"></script>
@endpush
