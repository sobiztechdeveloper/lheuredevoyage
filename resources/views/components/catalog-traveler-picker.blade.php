@props([
    'adult' => 2,
    'children' => 0,
    'infant' => 0,
    'label' => 'Travelers',
    'subLabel' => 'Guests',
])

@php
    $adultCount = max(1, (int) old('adult', $adult));
    $childrenCount = max(0, (int) old('children', $children));
    $infantCount = max(0, (int) old('infant', $infant));
@endphp

<div {{ $attributes->merge(['class' => 'form-group dropdown passenger-box holiday-traveler-picker']) }}>
    <div class="passenger-class" role="menu" data-bs-toggle="dropdown" aria-expanded="false">
        <label>{{ $label }}</label>
        <div class="form-group-icon">
            <div class="passenger-total holiday-passenger-summary">
                @include('partials.catalog.holiday-traveler-summary', [
                    'adult' => $adultCount,
                    'children' => $childrenCount,
                    'infant' => $infantCount,
                ])
            </div>
            <i class="fal fa-user-tie-hair"></i>
        </div>
        <p class="passenger-class-name">{{ $subLabel }}</p>
    </div>
    <div class="dropdown-menu dropdown-menu-end">
        <div class="dropdown-item">
            <div class="passenger-item">
                <div class="passenger-info">
                    <h6>Adults</h6>
                    <p>12+ Years</p>
                </div>
                <div class="passenger-qty">
                    <button type="button" class="minus-btn"><i class="far fa-minus"></i></button>
                    <input type="text" name="adult" class="qty-amount passenger-adult"
                        value="{{ $adultCount }}" readonly>
                    <button type="button" class="plus-btn"><i class="far fa-plus"></i></button>
                </div>
            </div>
        </div>
        <div class="dropdown-item">
            <div class="passenger-item">
                <div class="passenger-info">
                    <h6>Children</h6>
                    <p>2-12 Years</p>
                </div>
                <div class="passenger-qty">
                    <button type="button" class="minus-btn"><i class="far fa-minus"></i></button>
                    <input type="text" name="children" class="qty-amount passenger-children"
                        value="{{ $childrenCount }}" readonly>
                    <button type="button" class="plus-btn"><i class="far fa-plus"></i></button>
                </div>
            </div>
        </div>
        <div class="dropdown-item">
            <div class="passenger-item">
                <div class="passenger-info">
                    <h6>Infant</h6>
                    <p>Below 2 Years</p>
                </div>
                <div class="passenger-qty">
                    <button type="button" class="minus-btn"><i class="far fa-minus"></i></button>
                    <input type="text" name="infant" class="qty-amount passenger-infant"
                        value="{{ $infantCount }}" readonly>
                    <button type="button" class="plus-btn"><i class="far fa-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
