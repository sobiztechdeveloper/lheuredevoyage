@extends('layouts.app')
@section('body-class', 'home-3 fbw-page hbw-page')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/flight-booking-wizard.css') }}">
@endpush
@section('content')
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">Cruise booking request</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('cruise.show', $cruise) }}">{{ $cruise->name }}</a></li>
            <li class="active">Booking</li>
        </ul>
    </div>
</div>
<div class="py-80">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <div class="row mb-4 g-3 text-center">
            <div class="col-2"><span class="hbw-progress-label active" data-cbw-progress="1">1. Cruise</span></div>
            <div class="col-2"><span class="hbw-progress-label" data-cbw-progress="2">2. Dates</span></div>
            <div class="col-2"><span class="hbw-progress-label" data-cbw-progress="3">3. Contact</span></div>
            <div class="col-2"><span class="hbw-progress-label" data-cbw-progress="4">4. Passengers</span></div>
            <div class="col-2"><span class="hbw-progress-label" data-cbw-progress="5">5. Preferences</span></div>
            <div class="col-2"><span class="hbw-progress-label" data-cbw-progress="6">6. Review</span></div>
        </div>
        <form id="cbw-form" method="POST" action="{{ route('cruise.booking.store') }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="cruise_id" value="{{ $cruise->id }}">
            <input type="hidden" name="estimated_amount" value="{{ $context['estimated_amount'] }}">
            <input type="hidden" name="currency" value="{{ $context['currency'] }}">
            <div class="fbw-step-panel active" data-step="1"><div class="fbw-card"><h4 class="mb-3">Cruise Selection</h4><p><strong>{{ $cruise->name }}</strong> · {{ $cruise->location }}</p><p class="text-muted mb-3">Ship: {{ $cruise->ship_name ?: 'N/A' }}</p><div class="fbw-actions"><button type="button" class="fbw-btn-primary" data-cbw-next>Continue</button></div></div></div>
            <div class="fbw-step-panel" data-step="2"><div class="fbw-card"><h4 class="mb-3">Travel Dates</h4><div class="row g-3">
                <div class="col-md-4"><label class="form-label">Departure Date *</label><input type="date" name="departure_date" class="form-control" required value="{{ old('departure_date', $context['departure_date']->format('Y-m-d')) }}"></div>
                <div class="col-md-4"><label class="form-label">Return Date</label><input type="date" name="return_date" class="form-control" value="{{ old('return_date', $context['return_date']?->format('Y-m-d')) }}"></div>
                <div class="col-md-4"><label class="form-label">Cabin Type</label><input type="text" name="cabin_type" class="form-control" value="{{ old('cabin_type') }}" placeholder="Inside / Oceanview / Suite"></div>
                <div class="col-md-4"><label class="form-label">Adults *</label><input type="number" min="1" max="20" name="adults" class="form-control" required value="{{ old('adults', $context['adults']) }}"></div>
                <div class="col-md-4"><label class="form-label">Children</label><input type="number" min="0" max="20" name="children" class="form-control" value="{{ old('children', $context['children']) }}"></div>
                <div class="col-md-4"><label class="form-label">Infants</label><input type="number" min="0" max="10" name="infants" class="form-control" value="{{ old('infants', $context['infants']) }}"></div>
            </div><div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-cbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-cbw-next>Next</button></div></div></div>
            <div class="fbw-step-panel" data-step="3"><div class="fbw-card"><h4 class="mb-3">Contact Details</h4><div class="row g-3">
                <div class="col-md-6"><label class="form-label">Contact Name *</label><input type="text" name="contact_name" class="form-control" required value="{{ old('contact_name', auth()->user()?->name) }}"></div>
                <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="contact_email" class="form-control" required value="{{ old('contact_email', auth()->user()?->email) }}"></div>
                <div class="col-md-6"><label class="form-label">Phone *</label><input type="text" name="contact_phone" class="form-control" required value="{{ old('contact_phone') }}"></div>
                <div class="col-md-6"><label class="form-label">WhatsApp</label><input type="text" name="contact_whatsapp" class="form-control" value="{{ old('contact_whatsapp') }}"></div>
                <div class="col-md-6"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ old('country') }}"></div>
            </div><div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-cbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-cbw-next>Next</button></div></div></div>
            <div class="fbw-step-panel" data-step="4"><div class="fbw-card"><h4 class="mb-3">Passenger Details</h4>@include('pages.publicView.cruise.partials.cruise-booking-passenger-fields')<div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-cbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-cbw-next>Next</button></div></div></div>
            <div class="fbw-step-panel" data-step="5"><div class="fbw-card"><h4 class="mb-3">Preferences & Notes</h4><div class="row g-3">
                <div class="col-md-6"><label class="form-label">Dining Preference</label><input type="text" name="dining_preference" class="form-control" value="{{ old('dining_preference') }}"></div>
                <div class="col-md-6"><label class="form-label">Wheelchair Assistance</label><select name="wheelchair_assistance" class="form-select"><option value="0" @selected(old('wheelchair_assistance') == '0')>No</option><option value="1" @selected(old('wheelchair_assistance') == '1')>Yes</option></select></div>
                <div class="col-12"><label class="form-label">Dietary Requirements</label><textarea name="dietary_requirements" class="form-control" rows="2">{{ old('dietary_requirements') }}</textarea></div>
                <div class="col-12"><label class="form-label">Medical Conditions</label><textarea name="medical_conditions" class="form-control" rows="2">{{ old('medical_conditions') }}</textarea></div>
                <div class="col-12"><label class="form-label">Additional Notes</label><textarea name="additional_notes" class="form-control" rows="2">{{ old('additional_notes') }}</textarea></div>
            </div><div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-cbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-cbw-next>Next</button></div></div></div>
            <div class="fbw-step-panel" data-step="6"><div class="fbw-card"><h4 class="mb-3">Review & Submit</h4><div id="cbw-review-content" class="mb-3 p-3 rounded" style="background:#f8fafc;"></div><p class="small text-muted mb-2">This is a booking request only. Final confirmation will be shared by our travel consultant.</p><x-legal-booking-consent id="cruise-accept" /><div class="fbw-actions"><button type="button" class="fbw-btn-outline" data-cbw-prev>Back</button><button type="submit" class="fbw-btn-primary">Submit Request</button></div></div></div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
window.catalogBookingWizardSummary = {
    product: @json($cruise->name),
    location: @json($cruise->location),
    estimated: @json('$'.number_format($context['estimated_amount'], 2)),
    type: 'cruise'
};
</script>
<script src="{{ asset('assets/js/catalog-booking-wizard.js') }}?v={{ filemtime(public_path('assets/js/catalog-booking-wizard.js')) }}"></script>
@endpush
