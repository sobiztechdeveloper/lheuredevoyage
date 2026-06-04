@extends('layouts.app')
@section('body-class', 'home-3 fbw-page')

@push('styles')
<style>
.cruise-select-card,.cruise-cabin-card{border:2px solid #e8ecf3;border-radius:12px;padding:1.25rem;height:100%;cursor:pointer;transition:.2s}
.cruise-select-card.selected,.cruise-cabin-card.selected,.cruise-select-card:hover,.cruise-cabin-card:hover{border-color:var(--theme-color);box-shadow:0 8px 24px rgba(0,87,184,.12)}
.cruise-select-card input,.cruise-cabin-card input{position:absolute;opacity:0}
</style>
@endpush

@section('content')
@php
    $cruise = $preselected_cruise;
    $ctx = $context ?? [];
    $cruiseSteps = $cruise
        ? ['Summary', 'Cabin', 'Passengers', 'More Guests', 'Preferences', 'Emergency', 'Documents', 'Review']
        : ['Cruise', 'Cabin', 'Lead', 'Guests', 'Preferences', 'Emergency', 'Documents', 'Review'];
@endphp

<x-booking-wizard-layout
    title="Cruise Quote Request"
    :breadcrumbs="[
        ['label' => 'Cruises', 'url' => route('cruise')],
        ['label' => $cruise ? $cruise->displayName() : 'Request Quote', 'url' => $cruise ? route('cruise.show', $cruise) : null],
        ['label' => 'Quote Request'],
    ]"
    :steps="$cruiseSteps"
    :form-action="route('cruise.booking.store')"
    form-enctype="multipart/form-data"
>
    <x-slot:sidebar>
        @include('partials.booking.wizard-summary', ['summary' => $summary ?? []])
    </x-slot:sidebar>

    @csrf
    <input type="hidden" name="cruise_id" id="cruise_id" value="{{ old('cruise_id', $cruise?->id) }}">
    <input type="hidden" name="currency" value="{{ old('currency', $default_currency) }}">
    <input type="hidden" name="estimated_amount" id="estimated_amount" value="{{ old('estimated_amount', $ctx['estimated_amount'] ?? '') }}">

            {{-- Step 1: Select cruise --}}
            <div class="fbw-step-panel active" data-step="1">
                <div class="fbw-card">
                    <h4 class="fbw-card-title">Select Cruise</h4>
                    @if($cruise)
                        <div class="row g-3">
                            <div class="col-md-8">
                                <p class="text-muted mb-1">{{ $cruise->cruise_line }} · {{ $cruise->ship_name }}</p>
                                <h5>{{ $cruise->displayName() }}</h5>
                                <p>{{ $cruise->departure_port }} → {{ $cruise->arrival_port }} · {{ $cruise->duration_nights ?? $cruise->duration_days }} nights</p>
                                <p class="text-primary fw-semibold">From {{ $cruise->startingPriceDisplay() }}</p>
                                @if($cruise->itineraryDays->isNotEmpty())
                                <ul class="small ps-3">@foreach($cruise->itineraryDays->take(5) as $d)<li>Day {{ $d->day_number }}: {{ $d->port_name }}</li>@endforeach</ul>
                                @endif
                            </div>
                            @if($cruise->image_url && !str_contains($cruise->image_url, 'logo.png'))
                            <div class="col-md-4"><img src="{{ $cruise->image_url }}" class="img-fluid rounded" alt=""></div>
                            @endif
                        </div>
                        <div class="col-md-6 mt-3"><label class="form-label">Preferred departure date *</label>
                            <input type="date" name="departure_date" class="form-control" required value="{{ old('departure_date', isset($ctx['departure_date']) ? $ctx['departure_date']->format('Y-m-d') : '') }}">
                        </div>
                    @else
                        <div class="row g-3" id="cruise-picker">
                            @foreach($cruises as $c)
                            <div class="col-md-6 col-lg-4">
                                <label class="cruise-select-card d-block {{ old('cruise_id') == $c->id ? 'selected' : '' }}">
                                    <input type="radio" name="cruise_choice" value="{{ $c->id }}" data-cruise-select {{ old('cruise_id') == $c->id ? 'checked' : '' }}>
                                    <div class="small text-muted">{{ $c->cruise_line }}</div>
                                    <h5 class="mb-1">{{ $c->displayName() }}</h5>
                                    <p class="small mb-1">{{ $c->ship_name }} · {{ $c->duration_nights }} nights</p>
                                    <p class="mb-0 theme-btn btn-sm d-inline-block">{{ $c->startingPriceDisplay() }}</p>
                                </label>
                            </div>
                            @endforeach
                        </div>
                        <div class="mt-3"><label class="form-label">Departure date *</label>
                            <input type="date" name="departure_date" class="form-control" required value="{{ old('departure_date') }}">
                        </div>
                    @endif
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 2: Cabin --}}
            <div class="fbw-step-panel" data-step="2">
                <div class="fbw-card">
                    <h4 class="mb-3">Cabin selection</h4>
                    <div class="row g-3" id="cabin-picker">
                        @if($cruise && $cruise->cabins->isNotEmpty())
                            @foreach($cruise->cabins as $cabin)
                            <div class="col-md-6">
                                <label class="cruise-cabin-card d-block {{ old('cruise_cabin_id') == $cabin->id ? 'selected' : '' }}">
                                    <input type="radio" name="cruise_cabin_id" value="{{ $cabin->id }}" data-max-occupancy="{{ $cabin->max_occupancy }}" data-cabin-select {{ old('cruise_cabin_id') == $cabin->id ? 'checked' : '' }}>
                                    <span class="badge bg-light text-dark">{{ $cabin->cabinTypeLabel() }}</span>
                                    <h5 class="h6 mt-2">{{ $cabin->name }}</h5>
                                    <p class="small text-muted">{{ $cabin->description }}</p>
                                    <p class="mb-1">Max {{ $cabin->max_occupancy }} ({{ $cabin->max_adults }} adults, {{ $cabin->max_children }} children)</p>
                                    <p class="fw-semibold text-primary mb-0">{{ $cabin->formattedPrice() }}</p>
                                </label>
                            </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="row g-3 mt-3">
                        <div class="col-md-4"><label class="form-label">Adults *</label><input type="number" name="adults" id="adults" class="form-control" min="1" max="20" required value="{{ old('adults', $ctx['adults'] ?? 2) }}"></div>
                        <div class="col-md-4"><label class="form-label">Children</label><input type="number" name="children" id="children" class="form-control" min="0" max="20" value="{{ old('children', $ctx['children'] ?? 0) }}"></div>
                        <div class="col-md-4"><label class="form-label">Infants</label><input type="number" name="infants" id="infants" class="form-control" min="0" max="10" value="{{ old('infants', $ctx['infants'] ?? 0) }}"></div>
                        <div class="col-md-6"><label class="form-label">Return date</label><input type="date" name="return_date" class="form-control" value="{{ old('return_date', isset($ctx['return_date']) ? $ctx['return_date']?->format('Y-m-d') : '') }}"></div>
                    </div>
                    <p class="small text-muted mt-2" id="occupancy-hint"></p>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 3: Lead passenger --}}
            <div class="fbw-step-panel" data-step="3">
                <div class="fbw-card">
                    <h4 class="mb-3">Lead passenger</h4>
                    <div class="row g-3">
                        <div class="col-md-2"><label class="form-label">Title</label><input type="text" name="contact_title" class="form-control" value="{{ old('contact_title', 'Mr') }}"></div>
                        <div class="col-md-5"><label class="form-label">First name *</label><input type="text" name="contact_first_name" class="form-control" required value="{{ old('contact_first_name') }}"></div>
                        <div class="col-md-5"><label class="form-label">Last name *</label><input type="text" name="contact_last_name" class="form-control" required value="{{ old('contact_last_name') }}"></div>
                        <div class="col-md-4"><label class="form-label">Gender</label><select name="contact_gender" class="form-select"><option value="">—</option><option value="male" @selected(old('contact_gender') === 'male')>Male</option><option value="female" @selected(old('contact_gender') === 'female')>Female</option><option value="other" @selected(old('contact_gender') === 'other')>Other</option></select></div>
                        <div class="col-md-4"><label class="form-label">Date of birth *</label><input type="date" name="contact_date_of_birth" class="form-control" required value="{{ old('contact_date_of_birth') }}"></div>
                        <div class="col-md-4"><label class="form-label">Nationality *</label><input type="text" name="contact_nationality" class="form-control" required value="{{ old('contact_nationality', 'Switzerland') }}"></div>
                        <div class="col-md-4"><label class="form-label">Passport number *</label><input type="text" name="contact_passport_number" class="form-control" required value="{{ old('contact_passport_number') }}"></div>
                        <div class="col-md-4"><label class="form-label">Passport expiry *</label><input type="date" name="contact_passport_expiry" class="form-control" required value="{{ old('contact_passport_expiry') }}"></div>
                        <div class="col-md-4"><label class="form-label">Country of residence *</label><input type="text" name="country_of_residence" class="form-control" required value="{{ old('country_of_residence', 'Switzerland') }}"></div>
                        <div class="col-md-6"><label class="form-label">Email *</label><input type="email" name="contact_email" class="form-control" required value="{{ old('contact_email', auth()->user()?->email) }}"></div>
                        <div class="col-md-6"><label class="form-label">Mobile *</label><input type="text" name="contact_phone" class="form-control" required value="{{ old('contact_phone') }}"></div>
                        <div class="col-md-6"><label class="form-label">Address</label><input type="text" name="address" class="form-control" value="{{ old('address') }}"></div>
                        <div class="col-md-3"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ old('city') }}"></div>
                        <div class="col-md-3"><label class="form-label">Country</label><input type="text" name="country" class="form-control" value="{{ old('country', 'Switzerland') }}"></div>
                    </div>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 4: Additional passengers --}}
            <div class="fbw-step-panel" data-step="4">
                <div class="fbw-card">
                    <h4 class="mb-3">Additional passengers</h4>
                    <div id="additional-passengers">
                        @for($i = 0; $i < 3; $i++)
                        <div class="row g-3 border-bottom pb-3 mb-3 passenger-row">
                            <div class="col-md-2"><label class="form-label">Title</label><input type="text" name="passengers[{{ $i }}][title]" class="form-control" value="{{ old("passengers.$i.title") }}"></div>
                            <div class="col-md-4"><label class="form-label">First name</label><input type="text" name="passengers[{{ $i }}][first_name]" class="form-control" value="{{ old("passengers.$i.first_name") }}"></div>
                            <div class="col-md-4"><label class="form-label">Last name</label><input type="text" name="passengers[{{ $i }}][last_name]" class="form-control" value="{{ old("passengers.$i.last_name") }}"></div>
                            <div class="col-md-2"><label class="form-label">Type</label><select name="passengers[{{ $i }}][passenger_type]" class="form-select"><option value="adult">Adult</option><option value="child" @selected(old("passengers.$i.passenger_type") === 'child')>Child</option><option value="infant" @selected(old("passengers.$i.passenger_type") === 'infant')>Infant</option></select></div>
                            <div class="col-md-3"><label class="form-label">Gender</label><input type="text" name="passengers[{{ $i }}][gender]" class="form-control" value="{{ old("passengers.$i.gender") }}"></div>
                            <div class="col-md-3"><label class="form-label">Date of birth</label><input type="date" name="passengers[{{ $i }}][date_of_birth]" class="form-control" value="{{ old("passengers.$i.date_of_birth") }}"></div>
                            <div class="col-md-3"><label class="form-label">Nationality</label><input type="text" name="passengers[{{ $i }}][nationality]" class="form-control" value="{{ old("passengers.$i.nationality") }}"></div>
                            <div class="col-md-3"><label class="form-label">Passport</label><input type="text" name="passengers[{{ $i }}][passport_number]" class="form-control" value="{{ old("passengers.$i.passport_number") }}"></div>
                            <div class="col-md-4"><label class="form-label">Passport expiry</label><input type="date" name="passengers[{{ $i }}][passport_expiry]" class="form-control" value="{{ old("passengers.$i.passport_expiry") }}"></div>
                        </div>
                        @endfor
                    </div>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 5: Preferences --}}
            <div class="fbw-step-panel" data-step="5">
                <div class="fbw-card">
                    <h4 class="mb-3">Cruise preferences</h4>
                    <div class="row g-3">
                        <div class="col-md-4"><label class="form-label">Dining</label><select name="dining_preference" class="form-select"><option value="">—</option>@foreach($dining_preferences as $k => $l)<option value="{{ $k }}" @selected(old('dining_preference') === $k)>{{ $l }}</option>@endforeach</select></div>
                        <div class="col-md-4"><label class="form-label">Bed</label><select name="bed_preference" class="form-select"><option value="">—</option>@foreach($bed_preferences as $k => $l)<option value="{{ $k }}" @selected(old('bed_preference') === $k)>{{ $l }}</option>@endforeach</select></div>
                        <div class="col-md-4"><label class="form-label">Celebration</label><select name="celebration" class="form-select"><option value="">—</option>@foreach($celebrations as $k => $l)<option value="{{ $k }}" @selected(old('celebration') === $k)>{{ $l }}</option>@endforeach</select></div>
                        <div class="col-md-4"><label class="form-label">Wheelchair assistance</label><select name="wheelchair_assistance" class="form-select"><option value="0">No</option><option value="1" @selected(old('wheelchair_assistance'))>Yes</option></select></div>
                        <div class="col-md-4"><label class="form-label">Mobility assistance</label><select name="mobility_assistance" class="form-select"><option value="0">No</option><option value="1" @selected(old('mobility_assistance'))>Yes</option></select></div>
                        <div class="col-md-4"><label class="form-label">Special needs</label><select name="special_needs" class="form-select"><option value="0">No</option><option value="1" @selected(old('special_needs'))>Yes</option></select></div>
                    </div>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 6: Emergency --}}
            <div class="fbw-step-panel" data-step="6">
                <div class="fbw-card">
                    <h4 class="mb-3">Emergency contact & special requests</h4>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Contact name *</label><input type="text" name="emergency_contact_name" class="form-control" required value="{{ old('emergency_contact_name') }}"></div>
                        <div class="col-md-6"><label class="form-label">Relationship *</label><input type="text" name="emergency_contact_relationship" class="form-control" required value="{{ old('emergency_contact_relationship') }}"></div>
                        <div class="col-md-6"><label class="form-label">Phone *</label><input type="text" name="emergency_contact_phone" class="form-control" required value="{{ old('emergency_contact_phone') }}"></div>
                        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="emergency_contact_email" class="form-control" value="{{ old('emergency_contact_email') }}"></div>
                        <div class="col-12"><label class="form-label">Dietary requirements</label><textarea name="dietary_requirements" class="form-control" rows="2">{{ old('dietary_requirements') }}</textarea></div>
                        <div class="col-12"><label class="form-label">Medical conditions</label><textarea name="medical_conditions" class="form-control" rows="2">{{ old('medical_conditions') }}</textarea></div>
                        <div class="col-12"><label class="form-label">Special requests / notes</label><textarea name="special_requests" class="form-control" rows="3">{{ old('special_requests') }}</textarea></div>
                    </div>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 7: Documents --}}
            <div class="fbw-step-panel" data-step="7">
                <div class="fbw-card">
                    <h4 class="mb-3">Travel documents <span class="text-muted small">(optional)</span></h4>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Passport copy</label><input type="file" name="documents[passport]" class="form-control" accept=".pdf,image/*"></div>
                        <div class="col-md-6"><label class="form-label">Visa copy</label><input type="file" name="documents[visa]" class="form-control" accept=".pdf,image/*"></div>
                        <div class="col-md-6"><label class="form-label">Travel insurance</label><input type="file" name="documents[insurance]" class="form-control" accept=".pdf,image/*"></div>
                        <div class="col-md-6"><label class="form-label">Other</label><input type="file" name="documents[other]" class="form-control" accept=".pdf,image/*"></div>
                    </div>
                    <div class="fbw-actions mt-4"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="button" class="fbw-btn-primary" data-fbw-next>Continue</button></div>
                </div>
            </div>

            {{-- Step 8: Review --}}
            <div class="fbw-step-panel" data-step="8">
                <div class="fbw-card">
                    <h4 class="fbw-card-title">Review Your Request</h4>
                    <div class="fbw-review-section">
                        <h6>Quote summary</h6>
                        <div id="fbw-review-content"></div>
                    </div>
                    <p class="small text-muted mb-3">This is a <strong>cruise quote request</strong> only. Our team will prepare a personalised quotation — no payment is taken online.</p>
                    <x-legal-booking-consent id="cruise-quote-accept" />
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="accept_conditions" id="accept_conditions" value="1" required {{ old('accept_conditions') ? 'checked' : '' }}>
                        <label class="form-check-label" for="accept_conditions">I agree to the booking conditions for cruise quote requests.</label>
                    </div>
                    <div class="fbw-actions"><button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button><button type="submit" class="fbw-btn-primary">Request Cruise Quote</button></div>
                </div>
            </div>
</x-booking-wizard-layout>
@endsection
@push('scripts')
<script>
window.catalogBookingWizardSummary = {
    product: @json($preselected_cruise?->displayName() ?? 'Cruise'),
    location: @json($preselected_cruise ? ($preselected_cruise->departure_port.' → '.$preselected_cruise->arrival_port) : ''),
    type: 'cruise'
};
window.cruiseCabinsByCruise = @json($cruise_cabins_by_cruise ?? []);
</script>
@verbatim
<script>
document.querySelectorAll('[data-cruise-select]').forEach(function (el) {
    el.addEventListener('change', function () {
        document.getElementById('cruise_id').value = this.value;
        var card = this.closest('.cruise-select-card');
        if (card) {
            card.classList.add('selected');
        }
        renderCabins(this.value);
    });
});
document.querySelectorAll('[data-cabin-select]').forEach(function (el) {
    el.addEventListener('change', function () {
        updateOccupancyHint();
    });
});
function renderCabins(cruiseId) {
    var box = document.getElementById('cabin-picker');
    if (!box) {
        return;
    }
    var cabins = window.cruiseCabinsByCruise[cruiseId] || [];
    box.innerHTML = cabins.map(function (c) {
        return '<div class="col-md-6"><label class="cruise-cabin-card d-block">' +
            '<input type="radio" name="cruise_cabin_id" value="' + c.id + '" data-max-occupancy="' + c.max_occupancy + '" data-cabin-select>' +
            '<span class="badge bg-light text-dark">' + c.type + '</span>' +
            '<h5 class="h6 mt-2">' + c.name + '</h5>' +
            '<p class="small text-muted">' + (c.description || '') + '</p>' +
            '<p class="fw-semibold text-primary mb-0">' + c.price + '</p>' +
            '</label></div>';
    }).join('');
    box.querySelectorAll('[data-cabin-select]').forEach(function (el) {
        el.addEventListener('change', updateOccupancyHint);
    });
}
function updateOccupancyHint() {
    var sel = document.querySelector('[data-cabin-select]:checked');
    var max = sel ? parseInt(sel.getAttribute('data-max-occupancy'), 10) : null;
    var adultsEl = document.getElementById('adults');
    var childrenEl = document.getElementById('children');
    var infantsEl = document.getElementById('infants');
    var a = adultsEl ? parseInt(adultsEl.value || 0, 10) : 0;
    var c = childrenEl ? parseInt(childrenEl.value || 0, 10) : 0;
    var i = infantsEl ? parseInt(infantsEl.value || 0, 10) : 0;
    var hint = document.getElementById('occupancy-hint');
    if (!hint || !max) {
        return;
    }
    var total = a + c + i;
    hint.textContent = total > max
        ? 'Warning: ' + total + ' passengers exceeds cabin maximum (' + max + ').'
        : 'Up to ' + max + ' passengers for selected cabin.';
    hint.className = total > max ? 'small text-danger mt-2' : 'small text-muted mt-2';
}
var paxFieldIds = ['adults', 'children', 'infants'];
paxFieldIds.forEach(function (id) {
    var el = document.getElementById(id);
    if (el) {
        el.addEventListener('input', updateOccupancyHint);
    }
});
updateOccupancyHint();
</script>
@endverbatim
<script src="{{ asset('assets/js/booking-request-wizard.js') }}?v={{ filemtime(public_path('assets/js/booking-request-wizard.js')) }}"></script>
@endpush
