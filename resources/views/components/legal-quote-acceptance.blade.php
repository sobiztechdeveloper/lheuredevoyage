@php
    $bookingUrl = ($legalUrls ?? [])['booking-conditions'] ?? '#';
    $termsUrl = ($legalUrls ?? [])['terms-and-conditions'] ?? '#';
@endphp
<p class="text-muted small mb-3">
    By accepting this quotation you agree to our
    @if($bookingUrl !== '#')
        <a href="{{ $bookingUrl }}" target="_blank" rel="noopener">Booking Conditions</a>
    @else
        Booking Conditions
    @endif
    and
    @if($termsUrl !== '#')
        <a href="{{ $termsUrl }}" target="_blank" rel="noopener">Terms &amp; Conditions</a>.
    @else
        Terms &amp; Conditions.
    @endif
</p>
