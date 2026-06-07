@props([
    'name',
    'context' => null,
    'types' => null,
    'value' => null,
    'placeholder' => '',
    'format' => 'default',
    'label' => null,
    'icon' => 'fal fa-location-dot',
    'hint' => null,
    'class' => '',
    'inputClass' => 'form-control',
    'swap' => false,
    'disabled' => false,
    'serpapiId' => null,
])

@php
    $resolvedTypes = $types;

    if ($resolvedTypes === null && $context) {
        $resolvedTypes = \App\Enums\DestinationType::forContext($context);
    }

    $typesAttr = is_array($resolvedTypes) ? implode(',', $resolvedTypes) : (string) ($resolvedTypes ?? '');
    $fieldId = 'destination-' . preg_replace('/[^a-z0-9_-]/i', '-', $name) . '-' . uniqid();
    $inputClass = trim($inputClass . ' destination-autocomplete-input');
@endphp

<div class="form-group destination-autocomplete-wrap {{ $class }}" data-destination-autocomplete>
    @if($swap)
        <div class="search-form-swap"><i class="far fa-repeat"></i></div>
    @endif
    @if($label)
        <label for="{{ $fieldId }}">{{ $label }}</label>
    @endif
    <div class="form-group-icon">
        <input
            type="text"
            id="{{ $fieldId }}"
            name="{{ $name }}"
            class="{{ $inputClass }}"
            value="{{ $value }}"
            @if($placeholder) placeholder="{{ $placeholder }}" @endif
            autocomplete="off"
            data-destination-autocomplete-input
            data-context="{{ $context }}"
            data-types="{{ $typesAttr }}"
            data-format="{{ $format }}"
            data-api-url="{{ url('/api/destinations/search') }}"
            @if($disabled) disabled @endif
        >
        @if($icon)
            <i class="{{ $icon }}"></i>
        @endif
    </div>
    @if($hint)
        <p>{{ $hint }}</p>
    @else
        <p class="destination-field-meta" aria-hidden="true">&nbsp;</p>
    @endif
    @if($serpapiId && in_array($context, ['flight_from', 'flight_to'], true))
        <input
            type="hidden"
            name="{{ $context === 'flight_from' ? 'from_departure_id' : 'to_arrival_id' }}"
            value="{{ $serpapiId }}"
            data-serpapi-id="1"
        >
    @endif
</div>

@once
    @push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/destination-autocomplete.css') }}?v={{ file_exists(public_path('assets/css/destination-autocomplete.css')) ? filemtime(public_path('assets/css/destination-autocomplete.css')) : time() }}">
    @endpush
    @push('scripts')
    <script src="{{ asset('assets/js/destination-autocomplete.js') }}?v={{ file_exists(public_path('assets/js/destination-autocomplete.js')) ? filemtime(public_path('assets/js/destination-autocomplete.js')) : time() }}"></script>
    @endpush
@endonce
