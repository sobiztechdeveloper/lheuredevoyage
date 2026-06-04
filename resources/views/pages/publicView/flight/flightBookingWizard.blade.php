@extends('layouts.app')

@section('body-class', 'home-3 fbw-page')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/flight-booking-wizard.css') }}">
@endpush

@section('content')
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">Flight Booking Request</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            @if($search)
                <li><a href="{{ route('flight.search.results', $search) }}">Flight Results</a></li>
            @endif
            <li class="active">Booking Request</li>
        </ul>
    </div>
</div>

<div class="py-100 fbw-main">
    <div class="container">
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <ul class="fbw-progress" aria-label="Booking steps">
            <li data-step="1" class="active"><span class="fbw-step-dot">1</span><span class="fbw-step-label">Summary</span></li>
            <li data-step="2"><span class="fbw-step-dot">2</span><span class="fbw-step-label">Passengers</span></li>
            <li data-step="3"><span class="fbw-step-dot">3</span><span class="fbw-step-label">Contact</span></li>
            <li data-step="4"><span class="fbw-step-dot">4</span><span class="fbw-step-label">Preferences</span></li>
            <li data-step="5"><span class="fbw-step-dot">5</span><span class="fbw-step-label">Review</span></li>
        </ul>

        <form id="fbw-form" action="{{ route('flight.booking.store', $result) }}" method="POST" enctype="multipart/form-data" novalidate>
            @csrf
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="fbw-step-panel active" data-step="1">
                        <div class="fbw-card">
                            <h4 class="fbw-card-title">Your Selected Flight</h4>
                            <p class="text-muted mb-3">Please review your flight details. When ready, continue to enter passenger information.</p>
                            <div class="fbw-notice mb-0">
                                This is a <strong>booking request only</strong>. Our travel consultant will confirm availability and final pricing.
                            </div>
                            <div class="fbw-actions">
                                <a href="{{ $search ? route('flight.search.results', $search) : route('flight') }}" class="fbw-btn-outline">Back to Results</a>
                                <button type="button" class="fbw-btn-primary" data-fbw-next>Continue to Passengers</button>
                            </div>
                        </div>
                    </div>

                    <div class="fbw-step-panel" data-step="2">
                        <div class="fbw-card">
                            <h4 class="fbw-card-title">Passenger Details</h4>
                            <p class="text-muted">Enter details exactly as shown on each traveler's passport.</p>
                            <div id="fbw-passengers-container"
                                data-initial-count="{{ count($passengerSlots) }}"
                                data-max-passengers="9">
                                @include('pages.publicView.flight.partials.flight-booking-passenger-fields', ['passengerSlots' => $passengerSlots])
                                <div id="fbw-extra-passengers">
                                    @php $basePassengerCount = count($passengerSlots); @endphp
                                    @foreach(old('passengers', []) as $extraIndex => $extraPassenger)
                                        @if($extraIndex >= $basePassengerCount)
                                            @include('pages.publicView.flight.partials.flight-booking-passenger-extra', [
                                                'index' => $extraIndex,
                                                'label' => 'Additional Passenger '.($extraIndex - $basePassengerCount + 1),
                                                'passenger' => $extraPassenger,
                                            ])
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="mt-3">
                                <button type="button" class="fbw-btn-outline" id="fbw-add-passenger">
                                    <i class="far fa-plus"></i> Add More Passenger
                                </button>
                                <small class="text-muted d-block mt-2">Need to add another traveler? Use this to include extra passenger details.</small>
                            </div>
                            <template id="fbw-extra-passenger-tpl">
                                @include('pages.publicView.flight.partials.flight-booking-passenger-extra-tpl')
                            </template>

                            <div class="fbw-card mt-4" style="background:#f8fafd;">
                                <h5 class="mb-3" style="color:#162F65;">Booking Contact Passenger</h5>
                                <p class="text-muted small">Select who should be the main contact for this booking.</p>
                                <div id="fbw-contact-passenger-radios" class="d-flex flex-wrap gap-3">
                                    @foreach($passengerSlots as $index => $slot)
                                        <div class="form-check">
                                            <input class="form-check-input fbw-contact-passenger-radio" type="radio"
                                                name="contact_passenger_index" id="contact-passenger-{{ $index }}"
                                                value="{{ $index }}" @checked(old('contact_passenger_index', 0) == $index)>
                                            <label class="form-check-label" for="contact-passenger-{{ $index }}">{{ $slot['label'] }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('contact_passenger_index')<span class="fbw-invalid-inline d-block">{{ $message }}</span>@enderror
                            </div>

                            <div class="fbw-actions">
                                <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                                <button type="button" class="fbw-btn-primary" data-fbw-next>Continue to Contact</button>
                            </div>
                        </div>
                    </div>

                    <div class="fbw-step-panel" data-step="3">
                        <div class="fbw-card">
                            <h4 class="fbw-card-title">Contact Details</h4>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Contact Name <span class="text-danger">*</span></label>
                                    <input type="text" name="contact_name" id="fbw-contact-name" class="form-control @error('contact_name') is-invalid @enderror"
                                        value="{{ old('contact_name', auth()->user()?->name) }}" required readonly>
                                    <small class="text-muted">Auto-filled from the booking contact passenger selected in the previous step.</small>
                                    @error('contact_name')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Contact Email <span class="text-danger">*</span></label>
                                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', auth()->user()?->email) }}" required>
                                    <small class="text-muted">Booking updates will be sent to this email address.</small>
                                    @error('email')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Mobile Number <span class="text-danger">*</span></label>
                                    <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone') }}" required>
                                    @error('phone')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">WhatsApp Number</label>
                                    <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror"
                                        value="{{ old('whatsapp') }}">
                                    @error('whatsapp')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Country</label>
                                    <input type="text" name="country" class="form-control @error('country') is-invalid @enderror"
                                        value="{{ old('country') }}">
                                    @error('country')<span class="fbw-invalid-inline">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="fbw-actions">
                                <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                                <button type="button" class="fbw-btn-primary" data-fbw-next>Continue to Preferences</button>
                            </div>
                        </div>
                    </div>

                    <div class="fbw-step-panel" data-step="4">
                        <div class="fbw-card">
                            <h4 class="fbw-card-title">Travel Preferences</h4>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Preferred Airline</label>
                                    <input type="text" name="preferred_airline" class="form-control" value="{{ old('preferred_airline') }}"
                                        placeholder="e.g. Emirates, Qatar Airways">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Seat Preference</label>
                                    <select name="seat_preference" class="form-select">
                                        @foreach($seatPreferences as $value => $label)
                                            <option value="{{ $value }}" @selected(old('seat_preference', 'no_preference') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Meal Preference</label>
                                    <select name="meal_preference" class="form-select">
                                        @foreach($mealPreferences as $value => $label)
                                            <option value="{{ $value }}" @selected(old('meal_preference', 'no_preference') === $value)>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <h6 class="mb-2" style="color:#3361AC;">Special Assistance</h6>
                            <div class="fbw-checkbox-grid mb-3">
                                @foreach($assistanceOptions as $key => $label)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="special_assistance[{{ $key }}]" value="1"
                                            id="assist-{{ $key }}" @checked(old('special_assistance.'.$key))>
                                        <label class="form-check-label" for="assist-{{ $key }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mb-0">
                                <label class="form-label">Additional Notes</label>
                                <textarea name="special_requests" class="form-control" rows="4"
                                    placeholder="Preferred airline, flexible travel dates, special requests">{{ old('special_requests') }}</textarea>
                            </div>
                            <div class="fbw-actions">
                                <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                                <button type="button" class="fbw-btn-primary" data-fbw-next>Review Booking</button>
                            </div>
                        </div>
                    </div>

                    <div class="fbw-step-panel" data-step="5">
                        <div class="fbw-card">
                            <h4 class="fbw-card-title">Review Your Request</h4>
                            <div class="fbw-review-section">
                                <h6>Flight Summary</h6>
                                <div id="fbw-review-flight"></div>
                            </div>
                            <div class="fbw-review-section">
                                <h6>Passenger Summary</h6>
                                <div id="fbw-review-passengers"></div>
                            </div>
                            <div class="fbw-review-section">
                                <h6>Contact Information</h6>
                                <div id="fbw-review-contact"></div>
                            </div>
                            <div class="fbw-review-section">
                                <h6>Travel Preferences</h6>
                                <div id="fbw-review-preferences"></div>
                            </div>
                            <div class="fbw-notice">
                                <strong>This is a booking request only.</strong> Availability and final fare will be confirmed by our travel consultant before ticket issuance. No payment is required at this stage.
                            </div>
                            <x-legal-booking-consent id="accept_conditions" />
                            <div class="fbw-actions">
                                <button type="button" class="fbw-btn-outline" data-fbw-prev>Back</button>
                                <button type="submit" class="fbw-btn-primary">Submit Booking Request</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    @include('pages.publicView.flight.partials.flight-booking-summary', ['summary' => $summary])
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.fbwFlightSummary = @json($summary);
    window.fbwSeatLabels = @json($seatPreferences);
    window.fbwMealLabels = @json($mealPreferences);
    window.fbwAssistanceLabels = @json($assistanceOptions);
</script>
<script src="{{ asset('assets/js/flight-booking-wizard.js') }}"></script>
@if($errors->any())
<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('fbw-form');
    if (form && form.querySelector('.is-invalid')) {
        var panel = form.querySelector('.fbw-step-panel .is-invalid')?.closest('.fbw-step-panel');
        if (panel) {
            var step = parseInt(panel.dataset.step, 10);
            form.querySelectorAll('.fbw-step-panel').forEach(function (p) {
                p.classList.toggle('active', parseInt(p.dataset.step, 10) === step);
            });
        }
    }
});
</script>
@endif
@endpush
