@php $slots = $context['driver_slots'] ?? []; @endphp
<div class="row g-3">
    @foreach($slots as $index => $slot)
        <div class="col-12"><h6 class="fw-semibold mt-2 mb-2">{{ $slot['label'] }}</h6></div>
        <div class="col-md-2">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="drivers[{{ $index }}][title]" value="{{ old("drivers.$index.title", 'Mr') }}">
        </div>
        <div class="col-md-5">
            <label class="form-label">First Name *</label>
            <input type="text" class="form-control" name="drivers[{{ $index }}][first_name]" value="{{ old("drivers.$index.first_name") }}" required>
        </div>
        <div class="col-md-5">
            <label class="form-label">Last Name *</label>
            <input type="text" class="form-control" name="drivers[{{ $index }}][last_name]" value="{{ old("drivers.$index.last_name") }}" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Date of Birth</label>
            <input type="date" class="form-control" name="drivers[{{ $index }}][date_of_birth]" value="{{ old("drivers.$index.date_of_birth") }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Nationality</label>
            <input type="text" class="form-control" name="drivers[{{ $index }}][nationality]" value="{{ old("drivers.$index.nationality") }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">License Number</label>
            <input type="text" class="form-control" name="drivers[{{ $index }}][license_number]" value="{{ old("drivers.$index.license_number") }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">License Expiry</label>
            <input type="date" class="form-control" name="drivers[{{ $index }}][license_expiry]" value="{{ old("drivers.$index.license_expiry") }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Number</label>
            <input type="text" class="form-control" name="drivers[{{ $index }}][passport_number]" value="{{ old("drivers.$index.passport_number") }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">License File</label>
            <input type="file" class="form-control" name="drivers[{{ $index }}][license_file]" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport File</label>
            <input type="file" class="form-control" name="drivers[{{ $index }}][passport_file]" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="col-12"><hr class="my-2"></div>
    @endforeach
</div>
