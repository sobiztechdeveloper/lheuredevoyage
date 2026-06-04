@php
    $settingsUrl = route('cookie-settings');
    $policyUrl = ($legalUrls ?? [])['cookie-policy'] ?? null;
@endphp
<div id="cookie-consent-banner" class="cookie-consent-banner d-none" role="dialog" aria-label="Cookie consent">
    <div class="container">
        <div class="cookie-consent-inner">
            <p class="mb-0 me-3">
                This website uses cookies to improve your experience and analyze website traffic.
                @if($policyUrl)
                    <a href="{{ $policyUrl }}" class="text-white text-decoration-underline">Learn more</a>
                @endif
            </p>
            <div class="cookie-consent-actions flex-shrink-0">
                <button type="button" id="cookie-reject-non-essential" class="btn btn-sm btn-outline-light">Reject Non-Essential</button>
                <button type="button" id="cookie-open-settings" class="btn btn-sm btn-outline-light" data-url="{{ $settingsUrl }}">Cookie Settings</button>
                <button type="button" id="cookie-accept-all" class="btn btn-sm btn-warning text-dark fw-semibold">Accept All</button>
            </div>
        </div>
    </div>
</div>
<style>
.cookie-consent-banner {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    z-index: 9999;
    background: #162F65;
    color: #fff;
    padding: 1rem 0;
    box-shadow: 0 -4px 20px rgba(0,0,0,.15);
}
.cookie-consent-inner {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
}
.cookie-consent-actions {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
}
@media (max-width: 767px) {
    .cookie-consent-inner { flex-direction: column; align-items: stretch; }
    .cookie-consent-actions { justify-content: flex-start; }
}
</style>
