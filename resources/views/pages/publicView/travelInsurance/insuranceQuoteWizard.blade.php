@extends('layouts.app')
@section('body-class', 'home-3 fbw-page')

@push('styles')
<style>
.insurance-plan-card { border:2px solid #e8ecf3; border-radius:12px; padding:1.25rem; height:100%; cursor:pointer; transition:.2s; }
.insurance-plan-card.selected, .insurance-plan-card:hover { border-color:var(--theme-color); box-shadow:0 8px 24px rgba(0,87,184,.12); }
.insurance-plan-card input { position:absolute; opacity:0; }
</style>
@endpush

@section('content')
@php
    $plan = $preselected_plan ?? null;
@endphp

<x-booking-wizard-layout
    title="Insurance Quote Request"
    :breadcrumbs="[
        ['label' => 'Travel Insurance', 'url' => route('travelinsurance')],
        ['label' => $plan ? $plan->displayPlanName() : 'Request Quote', 'url' => $plan ? route('travelinsurance.show', $plan) : null],
        ['label' => 'Quote Request'],
    ]"
    :steps="['Travel', 'Plan', 'Primary', 'Additional', 'Risk & Docs', 'Review']"
    :form-action="route('travelinsurance.booking.store')"
    form-enctype="multipart/form-data"
>
    <x-slot:sidebar>
        @include('partials.booking.wizard-summary', ['summary' => $summary ?? []])
    </x-slot:sidebar>

    @csrf
    <input type="hidden" name="travel_insurance_id" id="travel_insurance_id" value="{{ old('travel_insurance_id', $plan?->id) }}">
    <input type="hidden" name="currency" value="{{ old('currency', $default_currency) }}">

            {{-- Step 1 Travel --}}
            <div class="fbw-step-panel active" data-step="1">
                <div class="fbw-card">
                    <h4 class="mb-3">Travel information</h4>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Destination country *</label><input type="text" name="destination_country" class="form-control" required value="{{ old('destination_country', ($searchDefaults ?? [])['destination_country'] ?? '') }}"></div>
                        <div class="col-md-6"><label class="form-label">Purpose of travel *</label>
                            <select name="purpose_of_travel" class="form-select" required>
                                <option value="">Select</option>
                                @foreach($travel_purposes as $key => $label)
                                    <option value="{{ $key }}" @selected(old('purpose_of_travel') === $key)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6"><label class="form-label">Departure date *</label><input type="date" name="travel_start" class="form-control" required value="{{ old('travel_start', ($searchDefaults ?? [])['travel_start'] ?? '') }}"></div>
                        <div class="col-md-6"><label class="form-label">Return date *</label><input type="date" name="travel_end" class="form-control" required value="{{ old('travel_end', ($searchDefaults ?? [])['travel_end'] ?? '') }}"></div>
                    </div>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 2 Plans --}}
            <div class="fbw-step-panel" data-step="2">
                <div class="fbw-card">
                    <h4 class="mb-3">Select insurance plan</h4>
                    <div class="row g-3">
                        @foreach($plans as $plan)
                            <div class="col-md-6 col-lg-4">
                                <label class="insurance-plan-card d-block {{ old('travel_insurance_id', $preselected_plan?->id) == $plan->id ? 'selected' : '' }}">
                                    <input type="radio" name="plan_choice" value="{{ $plan->id }}" {{ old('travel_insurance_id', $preselected_plan?->id) == $plan->id ? 'checked' : '' }} data-plan-select>
                                    <div class="small text-muted">{{ $plan->displayCompany() }}</div>
                                    <h5 class="mb-1">{{ $plan->displayPlanName() }}</h5>
                                    <p class="small mb-2">{{ $plan->planTypeLabel() }}</p>
                                    @if($plan->formattedMedicalCoverage())<p class="mb-1"><strong>Coverage:</strong> {{ $plan->formattedMedicalCoverage() }}</p>@endif
                                    <ul class="small ps-3 mb-2">@foreach($plan->keyBenefitsList(3) as $b)<li>{{ $b }}</li>@endforeach</ul>
                                    <p class="theme-btn btn-sm mb-0 d-inline-block">{{ $plan->displayPremium() }} @if($plan->price_per_person)<small>/ person</small>@endif</p>
                                </label>
                            </div>
                        @endforeach
                    </div>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 3 Primary traveler --}}
            <div class="fbw-step-panel" data-step="3">
                <div class="fbw-card">
                    <h4 class="mb-3">Primary traveler</h4>
                    <div class="row g-3">
                        <div class="col-md-2"><label class="form-label">Title</label><input type="text" name="travelers[0][title]" class="form-control" value="{{ old('travelers.0.title', 'Mr') }}"></div>
                        <div class="col-md-5"><label class="form-label">First name *</label><input type="text" name="travelers[0][first_name]" class="form-control" required value="{{ old('travelers.0.first_name', auth()->user()?->name) }}"></div>
                        <div class="col-md-5"><label class="form-label">Last name *</label><input type="text" name="travelers[0][last_name]" class="form-control" required value="{{ old('travelers.0.last_name') }}"></div>
                        <div class="col-md-4"><label class="form-label">Date of birth *</label><input type="date" name="travelers[0][date_of_birth]" class="form-control" required value="{{ old('travelers.0.date_of_birth') }}"></div>
                        <div class="col-md-4"><label class="form-label">Nationality *</label><input type="text" name="travelers[0][nationality]" class="form-control" required value="{{ old('travelers.0.nationality', 'Switzerland') }}"></div>
                        <div class="col-md-4"><label class="form-label">Passport number</label><input type="text" name="travelers[0][passport_number]" class="form-control" value="{{ old('travelers.0.passport_number') }}"></div>
                        <div class="col-md-4"><label class="form-label">Passport expiry</label><input type="date" name="travelers[0][passport_expiry]" class="form-control" value="{{ old('travelers.0.passport_expiry') }}"></div>
                        <div class="col-md-4"><label class="form-label">Email *</label><input type="email" name="contact_email" class="form-control" required value="{{ old('contact_email', auth()->user()?->email) }}"></div>
                        <div class="col-md-4"><label class="form-label">Phone *</label><input type="text" name="contact_phone" class="form-control" required value="{{ old('contact_phone') }}"></div>
                        <div class="col-md-6"><label class="form-label">Address</label><input type="text" name="address" class="form-control" value="{{ old('address') }}"></div>
                        <div class="col-md-3"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city') }}"></div>
                        <div class="col-md-3"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ old('country', 'Switzerland') }}"></div>
                    </div>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 4 Additional travelers --}}
            <div class="fbw-step-panel" data-step="4">
                <div class="fbw-card">
                    <h4 class="mb-3">Additional travelers <span class="text-muted small">(optional)</span></h4>
                    <div id="additional-travelers">
                        @for($i = 1; $i <= 2; $i++)
                        <div class="row g-3 border-bottom pb-3 mb-3">
                            <div class="col-md-5"><label class="form-label">Full name</label><input type="text" name="travelers[{{ $i }}][first_name]" class="form-control" placeholder="First name" value="{{ old("travelers.$i.first_name") }}"></div>
                            <div class="col-md-4"><label class="form-label">&nbsp;</label><input type="text" name="travelers[{{ $i }}][last_name]" class="form-control" placeholder="Last name" value="{{ old("travelers.$i.last_name") }}"></div>
                            <div class="col-md-3"><label class="form-label">Relationship</label><input type="text" name="travelers[{{ $i }}][relationship]" class="form-control" value="{{ old("travelers.$i.relationship") }}"></div>
                            <div class="col-md-4"><label class="form-label">Date of birth</label><input type="date" name="travelers[{{ $i }}][date_of_birth]" class="form-control" value="{{ old("travelers.$i.date_of_birth") }}"></div>
                            <div class="col-md-4"><label class="form-label">Nationality</label><input type="text" name="travelers[{{ $i }}][nationality]" class="form-control" value="{{ old("travelers.$i.nationality") }}"></div>
                            <div class="col-md-4"><label class="form-label">Passport</label><input type="text" name="travelers[{{ $i }}][passport_number]" class="form-control" value="{{ old("travelers.$i.passport_number") }}"></div>
                        </div>
                        @endfor
                    </div>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 5 Risk --}}
            <div class="fbw-step-panel" data-step="5">
                <div class="fbw-card">
                    <h4 class="mb-3">Risk assessment</h4>
                    <div class="row g-3">
                        @foreach(['pre_existing_conditions' => 'Pre-existing medical conditions', 'pregnancy' => 'Pregnancy', 'adventure_sports' => 'Adventure sports', 'winter_sports' => 'Winter sports', 'long_stay' => 'Long stay (90+ days)'] as $field => $label)
                        <div class="col-md-6"><label class="form-label">{{ $label }}</label><select name="{{ $field }}" class="form-select"><option value="0" @selected(!old($field))>No</option><option value="1" @selected(old($field))>Yes</option></select></div>
                        @endforeach
                        <div class="col-12"><label class="form-label">Additional notes</label><textarea name="additional_notes" class="form-control" rows="3">{{ old('additional_notes') }}</textarea></div>
                        <div class="col-md-6"><label class="form-label">Passport copy (optional)</label><input type="file" name="customer_documents[passport]" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div>
                        <div class="col-md-6"><label class="form-label">Visa / other document</label><input type="file" name="customer_documents[visa]" class="form-control" accept=".pdf,.jpg,.jpeg,.png"></div>
                    </div>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 6 Review & submit --}}
            <div class="fbw-step-panel" data-step="6">
                <div class="fbw-card">
                    <h4 class="fbw-card-title">Review Your Request</h4>
                    <div class="fbw-review-section">
                        <h6>Quote summary</h6>
                        <div id="fbw-review-content"></div>
                    </div>
                    <div class="fbw-notice">
                        <strong>This is a quote request only.</strong> Our consultants will contact you with a tailored offer. No payment is taken online.
                    </div>
                    <x-legal-booking-consent id="insurance-quote" />
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="privacy_accepted" value="1" id="privacy_accepted" required>
                        <label class="form-check-label" for="privacy_accepted">I accept the <a href="{{ route('legal.show', 'privacy-policy') }}" target="_blank">Privacy Policy</a>.</label>
                    </div>
                    <div class="fbw-actions">
                        <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                        <button type="submit" class="fbw-btn-primary">Request Insurance Quote</button>
                    </div>
                </div>
            </div>
</x-booking-wizard-layout>
@endsection
@push('scripts')
<script>
document.querySelectorAll('[data-plan-select]').forEach(function (r) {
    r.addEventListener('change', function () {
        document.getElementById('travel_insurance_id').value = this.value;
        document.querySelectorAll('.insurance-plan-card').forEach(function (c) { c.classList.remove('selected'); });
        var card = this.closest('.insurance-plan-card');
        if (card) { card.classList.add('selected'); }
    });
});
window.catalogBookingWizardSummary = { type: 'insurance' };
</script>
<script src="{{ asset('assets/js/booking-request-wizard.js') }}?v={{ filemtime(public_path('assets/js/booking-request-wizard.js')) }}"></script>
@endpush
