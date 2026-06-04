@props(['id' => 'legal-accept', 'errorKey' => 'accept_conditions'])

@php
    $termsUrl = ($legalUrls ?? [])['terms-and-conditions'] ?? '#';
    $privacyUrl = ($legalUrls ?? [])['privacy-policy'] ?? '#';
@endphp
<div class="form-check mt-3">
    <input class="form-check-input @error($errorKey) is-invalid @enderror" type="checkbox"
        name="accept_conditions" value="1" id="{{ $id }}" required>
    <label class="form-check-label" for="{{ $id }}">
        I agree to the
        @if($termsUrl !== '#')
            <a href="{{ $termsUrl }}" target="_blank" rel="noopener">Terms &amp; Conditions</a>
        @else
            Terms &amp; Conditions
        @endif
        and
        @if($privacyUrl !== '#')
            <a href="{{ $privacyUrl }}" target="_blank" rel="noopener">Privacy Policy</a>
        @else
            Privacy Policy
        @endif
    </label>
    @error($errorKey)<span class="hbw-invalid-inline fbw-invalid-inline d-block">{{ $message }}</span>@enderror
</div>
