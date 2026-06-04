@props([
    'name' => 'travelers',
    'value' => null,
    'min' => 1,
    'max' => 20,
])

@php
    $count = (int) old($name, $value ?? request($name, 1));
    $count = max($min, min($max, $count));
@endphp

<div class="form-group dropdown passenger-box insurance-traveler-picker">
    <div class="passenger-class" role="menu" data-bs-toggle="dropdown" aria-expanded="false">
        <label>Number of Travelers</label>
        <div class="form-group-icon">
            <div class="passenger-total">
                <span class="insurance-traveler-total">{{ $count }}</span>
                {{ $count === 1 ? 'Traveler' : 'Travelers' }}
            </div>
            <i class="fal fa-users"></i>
        </div>
    </div>
    <div class="dropdown-menu dropdown-menu-end">
        <div class="dropdown-item">
            <div class="passenger-item">
                <div class="passenger-info">
                    <h6 class="mb-0">Travelers</h6>
                    <p class="mb-0 small text-muted">Including yourself</p>
                </div>
                <div class="passenger-qty">
                    <button type="button" class="minus-btn" @if($count <= $min) disabled @endif><i class="far fa-minus"></i></button>
                    <input type="text"
                        name="{{ $name }}"
                        class="qty-amount insurance-traveler-count"
                        value="{{ $count }}"
                        data-min="{{ $min }}"
                        data-max="{{ $max }}"
                        readonly>
                    <button type="button" class="plus-btn" @if($count >= $max) disabled @endif><i class="far fa-plus"></i></button>
                </div>
            </div>
        </div>
    </div>
</div>
