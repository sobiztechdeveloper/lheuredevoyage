@props([
    'name',
    'id',
    'options' => [],
    'placeholder' => null,
    'labels' => [],
])

<div class="hpr-multiselect dropdown w-100" data-hpr-multiselect>
    <button
        type="button"
        class="form-select form-select-sm hpr-ms-toggle text-start dropdown-toggle w-100"
        data-bs-toggle="dropdown"
        data-bs-auto-close="outside"
        aria-expanded="false"
        id="{{ $id }}-toggle"
    >
        <span class="hpr-ms-summary text-muted">{{ $placeholder ?? __('holiday_package_request.not_specified') }}</span>
    </button>
    <div class="dropdown-menu p-2 w-100 shadow-sm hpr-ms-menu">
        <input type="search" class="form-control form-control-sm hpr-ms-search mb-2" placeholder="Search..." autocomplete="off" aria-label="Search options">
        <div class="hpr-ms-options">
            @if(count($options) > 0)
                <label class="hpr-ms-option hpr-ms-select-all d-flex align-items-center gap-2 py-1 px-1 mb-0" for="{{ $id }}-select-all">
                    <input
                        type="checkbox"
                        class="form-check-input m-0 flex-shrink-0 hpr-ms-select-all-input"
                        id="{{ $id }}-select-all"
                        aria-label="{{ __('holiday_package_request.select_all') }}"
                    >
                    <span class="hpr-ms-option-label">{{ __('holiday_package_request.select_all') }}</span>
                </label>
            @endif
            @foreach($options as $key)
                @php
                    $label = $labels[$key] ?? $key;
                @endphp
                <label class="hpr-ms-option d-flex align-items-center gap-2 py-1 px-1 mb-0" for="{{ $id }}-{{ $key }}">
                    <input
                        type="checkbox"
                        class="form-check-input m-0 flex-shrink-0"
                        name="{{ $name }}[]"
                        value="{{ $key }}"
                        id="{{ $id }}-{{ $key }}"
                        data-label="{{ $label }}"
                    >
                    <span class="hpr-ms-option-label">{{ $label }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>
