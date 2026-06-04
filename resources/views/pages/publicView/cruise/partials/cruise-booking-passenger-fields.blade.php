@php $slots = $context['passenger_slots'] ?? []; @endphp
<div class="row g-3">
    @foreach($slots as $index => $slot)
        <div class="col-12"><h6 class="fw-semibold mt-2 mb-2">{{ $slot['label'] }}</h6></div>
        <div class="col-md-2">
            <label class="form-label">Title</label>
            <input type="text" class="form-control" name="passengers[{{ $index }}][title]" value="{{ old("passengers.$index.title", 'Mr') }}">
        </div>
        <div class="col-md-5">
            <label class="form-label">First Name *</label>
            <input type="text" class="form-control" name="passengers[{{ $index }}][first_name]" value="{{ old("passengers.$index.first_name") }}" required>
        </div>
        <div class="col-md-5">
            <label class="form-label">Last Name *</label>
            <input type="text" class="form-control" name="passengers[{{ $index }}][last_name]" value="{{ old("passengers.$index.last_name") }}" required>
        </div>
        <input type="hidden" name="passengers[{{ $index }}][passenger_type]" value="{{ $slot['type'] }}">
        <div class="col-md-3">
            <label class="form-label">Gender</label>
            <select class="form-select" name="passengers[{{ $index }}][gender]">
                <option value="">Select</option>
                @foreach(['male' => 'Male', 'female' => 'Female', 'other' => 'Other'] as $key => $label)
                    <option value="{{ $key }}" @selected(old("passengers.$index.gender") === $key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label">Date of Birth</label>
            <input type="date" class="form-control" name="passengers[{{ $index }}][date_of_birth]" value="{{ old("passengers.$index.date_of_birth") }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Nationality</label>
            <input type="text" class="form-control" name="passengers[{{ $index }}][nationality]" value="{{ old("passengers.$index.nationality") }}">
        </div>
        <div class="col-md-3">
            <label class="form-label">Passport Number</label>
            <input type="text" class="form-control" name="passengers[{{ $index }}][passport_number]" value="{{ old("passengers.$index.passport_number") }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Expiry</label>
            <input type="date" class="form-control" name="passengers[{{ $index }}][passport_expiry]" value="{{ old("passengers.$index.passport_expiry") }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport Country</label>
            <input type="text" class="form-control" name="passengers[{{ $index }}][passport_country]" value="{{ old("passengers.$index.passport_country") }}">
        </div>
        <div class="col-md-4">
            <label class="form-label">Passport File</label>
            <input type="file" class="form-control" name="passengers[{{ $index }}][passport_file]" accept=".pdf,.jpg,.jpeg,.png">
        </div>
        <div class="col-12"><hr class="my-2"></div>
    @endforeach
</div>
