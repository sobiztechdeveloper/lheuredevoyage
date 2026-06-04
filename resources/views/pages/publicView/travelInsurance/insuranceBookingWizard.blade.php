@extends('layouts.app')
@section('body-class', 'home-3 fbw-page hbw-page')
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/flight-booking-wizard.css') }}">
@endpush
@section('content')
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">Insurance booking request</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('travelinsurance.show', $travelInsurance) }}">{{ $travelInsurance->name }}</a></li>
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
            <div class="col-3"><span class="hbw-progress-label active" data-cbw-progress="1">1. Plan</span></div>
            <div class="col-3"><span class="hbw-progress-label" data-cbw-progress="2">2. Travel</span></div>
            <div class="col-3"><span class="hbw-progress-label" data-cbw-progress="3">3. Travelers</span></div>
            <div class="col-3"><span class="hbw-progress-label" data-cbw-progress="4">4. Review</span></div>
        </div>
        <form id="cbw-form" method="POST" action="{{ route('travelinsurance.booking.store') }}">
            @csrf
            <input type="hidden" name="travel_insurance_id" value="{{ $travelInsurance->id }}">
            <input type="hidden" name="estimated_amount" value="{{ $context['estimated_amount'] }}">
            <input type="hidden" name="currency" value="{{ $context['currency'] }}">
            <div class="fbw-step-panel active" data-step="1"><div class="fbw-card"><h4 class="mb-3">Plan Selection</h4><p><strong>{{ $travelInsurance->name }}</strong> · {{ $travelInsurance->location }}</p><p class="text-muted mb-3">Coverage: {{ $travelInsurance->coverage ?: 'Standard' }}</p><div class="fbw-actions"><button type="button" class="fbw-btn-primary" data-cbw-next>Continue</button></div></div></div>
            <div class="fbw-step-panel" data-step="2"><div class="fbw-card"><h4 class="mb-3">Travel & Contact</h4><div class="row g-3">
                <div class="col-md-6"><label class="form-label">Destination</label><input type="text" name="destination" class="form-control" value="{{ old('destination') }}"></div>
                <div class="col-md-3"><label class="form-label">Travel Start *</label><input type="date" name="travel_start" class="form-control" required value="{{ old('travel_start', $context['travel_start']->format('Y-m-d')) }}"></div>
                <div class="col-md-3"><label class="form-label">Travel End *</label><input type="date" name="travel_end" class="form-control" required value="{{ old('travel_end', $context['travel_end']->format('Y-m-d')) }}"></div>
                <div class="col-md-6"><label class="form-label">Coverage Type</label><input type="text" name="coverage_type" class="form-control" value="{{ old('coverage_type') }}"></div>
                <div class="col-md-6"><label class="form-label">Pre-existing Conditions</label><select name="pre_existing_conditions" class="form-select"><option value="0" @selected(old('pre_existing_conditions') == '0')>No</option><option value="1" @selected(old('pre_existing_conditions') == '1')>Yes</option></select></div>
                <div class="col-12"><label class="form-label">Medical Notes</label><textarea name="medical_notes" class="form-control" rows="2">{{ old('medical_notes') }}</textarea></div>
                <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="contact_email" class="form-control" required value="{{ old('contact_email', auth()->user()?->email) }}"></div>
                <div class="col-md-6"><label class="form-label">Phone *</label><input type="text" name="contact_phone" class="form-control" required value="{{ old('contact_phone') }}"></div>
                <div class="col-md-6"><label class="form-label">WhatsApp</label><input type="text" name="contact_whatsapp" class="form-control" value="{{ old('contact_whatsapp') }}"></div>
                <div class="col-md-6"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ old('country') }}"></div>
                <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea></div>
            </div><div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-cbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-cbw-next>Next</button></div></div></div>
            <div class="fbw-step-panel" data-step="3"><div class="fbw-card"><h4 class="mb-3">Travelers</h4><div class="row g-3">
                @php $slots = $context['traveler_slots'] ?? []; @endphp
                @foreach($slots as $index => $slot)
                    <div class="col-12"><h6 class="fw-semibold mt-2 mb-2">{{ $slot['label'] }}</h6></div>
                    <div class="col-md-2"><label class="form-label">Title</label><input type="text" class="form-control" name="travelers[{{ $index }}][title]" value="{{ old("travelers.$index.title", 'Mr') }}"></div>
                    <div class="col-md-5"><label class="form-label">First Name *</label><input type="text" class="form-control" name="travelers[{{ $index }}][first_name]" value="{{ old("travelers.$index.first_name") }}" required></div>
                    <div class="col-md-5"><label class="form-label">Last Name *</label><input type="text" class="form-control" name="travelers[{{ $index }}][last_name]" value="{{ old("travelers.$index.last_name") }}" required></div>
                    <div class="col-md-4"><label class="form-label">Date of Birth</label><input type="date" class="form-control" name="travelers[{{ $index }}][date_of_birth]" value="{{ old("travelers.$index.date_of_birth") }}"></div>
                    <div class="col-md-4"><label class="form-label">Nationality</label><input type="text" class="form-control" name="travelers[{{ $index }}][nationality]" value="{{ old("travelers.$index.nationality") }}"></div>
                    <div class="col-md-4"><label class="form-label">Passport Number</label><input type="text" class="form-control" name="travelers[{{ $index }}][passport_number]" value="{{ old("travelers.$index.passport_number") }}"></div>
                    <div class="col-12"><hr class="my-2"></div>
                @endforeach
            </div><div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-cbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-cbw-next>Next</button></div></div></div>
            <div class="fbw-step-panel" data-step="4"><div class="fbw-card"><h4 class="mb-3">Review & Submit</h4><div id="cbw-review-content" class="mb-3 p-3 rounded" style="background:#f8fafc;"></div><p class="small text-muted mb-2">This is a booking request only. Final confirmation will be shared by our travel consultant.</p><x-legal-booking-consent id="insurance-accept" /><div class="fbw-actions"><button type="button" class="fbw-btn-outline" data-cbw-prev>Back</button><button type="submit" class="fbw-btn-primary">Submit Request</button></div></div></div>
        </form>
    </div>
</div>
@endsection
@push('scripts')
<script>
window.catalogBookingWizardSummary = {
    product: @json($travelInsurance->name),
    location: @json($travelInsurance->location),
    estimated: @json('$'.number_format($context['estimated_amount'], 2)),
    type: 'insurance'
};
</script>
<script src="{{ asset('assets/js/catalog-booking-wizard.js') }}?v={{ filemtime(public_path('assets/js/catalog-booking-wizard.js')) }}"></script>
@endpush
