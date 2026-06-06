@props([
    'adult' => 2,
    'children' => 0,
    'infant' => 0,
    'label' => 'Number of Travelers',
])

@php
    $adultCount = max(1, (int) old('adult', $adult));
    $childrenCount = max(0, (int) old('children', $children));
    $infantCount = max(0, (int) old('infant', $infant));
@endphp

<div {{ $attributes->merge(['class' => 'catalog-traveler-fields']) }}>
    <label class="form-label fw-semibold">{{ $label }}</label>
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label small text-muted mb-1">Adults <span class="text-danger">*</span></label>
            <input type="number" name="adult" class="form-control" min="1" max="20" required value="{{ $adultCount }}">
            <span class="form-text">12+ years</span>
        </div>
        <div class="col-md-4">
            <label class="form-label small text-muted mb-1">Children</label>
            <input type="number" name="children" class="form-control" min="0" max="20" value="{{ $childrenCount }}">
            <span class="form-text">2–12 years</span>
        </div>
        <div class="col-md-4">
            <label class="form-label small text-muted mb-1">Infants</label>
            <input type="number" name="infant" class="form-control" min="0" max="10" value="{{ $infantCount }}">
            <span class="form-text">Under 2 years</span>
        </div>
    </div>
</div>
