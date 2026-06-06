@php
    $wrapperClass = $class ?? 'col-12';
@endphp

<div class="{{ $wrapperClass }}">
    <label class="admin-field-label">{{ $label }}</label>
    @if(!empty($hint))
        <p class="text-muted small mb-2">{{ $hint }}</p>
    @endif
    <div class="admin-upload-zone" data-featured-upload>
        <input type="file" name="{{ $name }}" class="d-none @error($name) is-invalid @enderror" accept="{{ $accept ?? 'image/*' }}">
        <i class="far fa-cloud-arrow-up fa-2x text-muted mb-2"></i>
        <p class="small text-muted mb-0">Click to upload or drag image here</p>
        <div class="admin-upload-preview {{ !empty($currentUrl) ? '' : 'd-none' }}" data-preview>
            <img src="{{ $currentUrl ?? '' }}" alt="Preview">
            <button type="button" class="admin-upload-remove" data-remove-preview aria-label="Remove"><i class="far fa-xmark"></i></button>
        </div>
    </div>
    @error($name)<div class="admin-field-error">{{ $message }}</div>@enderror
</div>
