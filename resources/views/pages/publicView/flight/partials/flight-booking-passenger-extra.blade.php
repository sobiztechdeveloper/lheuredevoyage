@php
    $prefix = "passengers.{$index}";
    $passenger = $passenger ?? [];
    $label = $label ?? 'Additional Passenger';
@endphp
<div class="fbw-passenger-block fbw-passenger-block--extra" data-passenger-index="{{ $index }}" data-passenger-label="{{ $label }}" data-extra-passenger="1">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0">{{ $label }}</h5>
        <button type="button" class="btn btn-sm btn-outline-danger fbw-remove-passenger" aria-label="Remove passenger">
            <i class="far fa-times"></i> Remove
        </button>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <label class="form-label">Passenger Type <span class="text-danger">*</span></label>
            <select name="passengers[{{ $index }}][passenger_type]" class="form-select" required>
                <option value="adult" @selected(($passenger['passenger_type'] ?? 'adult') === 'adult')>Adult</option>
                <option value="child" @selected(($passenger['passenger_type'] ?? '') === 'child')>Child</option>
                <option value="infant" @selected(($passenger['passenger_type'] ?? '') === 'infant')>Infant</option>
            </select>
        </div>
    </div>
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Title <span class="text-danger">*</span></label>
            <select name="passengers[{{ $index }}][title]" class="form-select" required>
                <option value="">Select</option>
                @foreach(['Mr', 'Mrs', 'Ms', 'Master', 'Miss'] as $title)
                    <option value="{{ $title }}" @selected(($passenger['title'] ?? '') === $title)>{{ $title }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">First Name <span class="text-danger">*</span></label>
            <input type="text" name="passengers[{{ $index }}][first_name]" class="form-control" value="{{ $passenger['first_name'] ?? '' }}" required>
        </div>
        <div class="col-md-5">
            <label class="form-label">Last Name <span class="text-danger">*</span></label>
            <input type="text" name="passengers[{{ $index }}][last_name]" class="form-control" value="{{ $passenger['last_name'] ?? '' }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Date of Birth <span class="text-danger">*</span></label>
            <input type="date" name="passengers[{{ $index }}][date_of_birth]" class="form-control" value="{{ $passenger['date_of_birth'] ?? '' }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Gender <span class="text-danger">*</span></label>
            <select name="passengers[{{ $index }}][gender]" class="form-select" required>
                <option value="">Select</option>
                <option value="male" @selected(($passenger['gender'] ?? '') === 'male')>Male</option>
                <option value="female" @selected(($passenger['gender'] ?? '') === 'female')>Female</option>
                <option value="other" @selected(($passenger['gender'] ?? '') === 'other')>Other</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Nationality <span class="text-danger">*</span></label>
            <input type="text" name="passengers[{{ $index }}][nationality]" class="form-control" value="{{ $passenger['nationality'] ?? '' }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Number <span class="text-danger">*</span></label>
            <input type="text" name="passengers[{{ $index }}][passport_number]" class="form-control" value="{{ $passenger['passport_number'] ?? '' }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Expiry <span class="text-danger">*</span></label>
            <input type="date" name="passengers[{{ $index }}][passport_expiry]" class="form-control" value="{{ $passenger['passport_expiry'] ?? '' }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Issuing Country</label>
            <input type="text" name="passengers[{{ $index }}][passport_country]" class="form-control" value="{{ $passenger['passport_country'] ?? '' }}">
        </div>
        @php $prefix = "passengers.{$index}"; @endphp
        @include('pages.publicView.flight.partials.flight-booking-passport-upload', ['index' => $index, 'prefix' => $prefix])
    </div>
</div>
