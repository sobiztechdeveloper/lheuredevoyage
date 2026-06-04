@extends('layouts.app')
@section('title', 'Cookie Settings')
@section('meta_description', 'Manage your cookie preferences for this website.')
@section('content')
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">Cookie Settings</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="active">Cookie Settings</li>
        </ul>
    </div>
</div>
<div class="py-80">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="user-profile-card p-4" style="background:#fff;border-radius:8px;box-shadow:0 2px 12px rgba(0,0,0,.06);">
                    <p class="text-muted">Choose which optional cookies we may use. Necessary cookies are always active for security and consent storage.</p>
                    @if($cookiePolicyUrl)
                        <p class="small"><a href="{{ $cookiePolicyUrl }}">Read our Cookie Policy</a></p>
                    @endif
                    <form id="cookie-settings-form" class="mt-4">
                        <div class="mb-3 p-3 rounded" style="background:#f8fafc;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" checked disabled>
                                <label class="form-check-label"><strong>Necessary Cookies</strong> (required)</label>
                            </div>
                            <p class="small text-muted mb-0 ms-4">Essential for website security and remembering your consent.</p>
                        </div>
                        <div class="mb-3 p-3 border rounded">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="analytics" id="cookie-analytics" value="1">
                                <label class="form-check-label" for="cookie-analytics"><strong>Analytics Cookies</strong></label>
                            </div>
                            <p class="small text-muted mb-0 ms-4">Help us understand how visitors use our website.</p>
                        </div>
                        <div class="mb-3 p-3 border rounded">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="marketing" id="cookie-marketing" value="1">
                                <label class="form-check-label" for="cookie-marketing"><strong>Marketing Cookies</strong></label>
                            </div>
                            <p class="small text-muted mb-0 ms-4">Used for advertising and remarketing where applicable.</p>
                        </div>
                        <div class="mb-4 p-3 border rounded">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="preferences" id="cookie-preferences" value="1">
                                <label class="form-check-label" for="cookie-preferences"><strong>Preference Cookies</strong></label>
                            </div>
                            <p class="small text-muted mb-0 ms-4">Remember your settings and preferences.</p>
                        </div>
                        <button type="submit" class="theme-btn">Save Preferences</button>
                        <p id="cookie-settings-msg" class="small text-success mt-3 mb-0" style="display:none;">Preferences saved.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{ asset('assets/js/cookie-consent.js') }}?v={{ filemtime(public_path('assets/js/cookie-consent.js')) }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var saved = window.LdvCookieConsent && window.LdvCookieConsent.getStored();
    if (saved) {
        document.getElementById('cookie-analytics').checked = !!saved.analytics;
        document.getElementById('cookie-marketing').checked = !!saved.marketing;
        document.getElementById('cookie-preferences').checked = !!saved.preferences;
    }
    document.getElementById('cookie-settings-form').addEventListener('submit', function (e) {
        e.preventDefault();
        window.LdvCookieConsent.saveCustom({
            analytics: document.getElementById('cookie-analytics').checked,
            marketing: document.getElementById('cookie-marketing').checked,
            preferences: document.getElementById('cookie-preferences').checked,
        }).then(function () {
            var msg = document.getElementById('cookie-settings-msg');
            msg.style.display = 'block';
        });
    });
});
</script>
@endpush
