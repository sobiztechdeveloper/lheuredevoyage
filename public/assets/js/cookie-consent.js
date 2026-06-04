(function () {
    'use strict';

    var STORAGE_KEY = 'ldv_cookie_prefs';
    var CONSENT_FLAG = 'ldv_consent_given';
    var endpoint = document.querySelector('meta[name="cookie-consent-url"]');
    var postUrl = endpoint ? endpoint.getAttribute('content') : '/cookie-consent';
    var csrf = document.querySelector('meta[name="csrf-token"]');

    function getStored() {
        try {
            var raw = localStorage.getItem(STORAGE_KEY);
            return raw ? JSON.parse(raw) : null;
        } catch (e) {
            return null;
        }
    }

    function setStored(prefs) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(prefs));
        localStorage.setItem(CONSENT_FLAG, '1');
    }

    function postConsent(payload) {
        return fetch(postUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : '',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: JSON.stringify(payload),
            credentials: 'same-origin',
        }).then(function (res) {
            return res.json();
        }).then(function (data) {
            if (data && data.preferences) {
                setStored(data.preferences);
            }
            hideBanner();
            return data;
        });
    }

    function hideBanner() {
        var banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.classList.add('d-none');
        }
    }

    function showBanner() {
        if (localStorage.getItem(CONSENT_FLAG)) {
            return;
        }
        var banner = document.getElementById('cookie-consent-banner');
        if (banner) {
            banner.classList.remove('d-none');
        }
    }

    window.LdvCookieConsent = {
        getStored: getStored,
        acceptAll: function () {
            return postConsent({ choice: 'accept_all' });
        },
        rejectNonEssential: function () {
            return postConsent({ choice: 'reject_non_essential' });
        },
        saveCustom: function (prefs) {
            return postConsent({
                choice: 'custom',
                analytics: !!prefs.analytics,
                marketing: !!prefs.marketing,
                preferences: !!prefs.preferences,
            });
        },
    };

    document.addEventListener('DOMContentLoaded', function () {
        showBanner();

        var acceptBtn = document.getElementById('cookie-accept-all');
        var rejectBtn = document.getElementById('cookie-reject-non-essential');
        var settingsBtn = document.getElementById('cookie-open-settings');

        if (acceptBtn) {
            acceptBtn.addEventListener('click', function () {
                window.LdvCookieConsent.acceptAll();
            });
        }
        if (rejectBtn) {
            rejectBtn.addEventListener('click', function () {
                window.LdvCookieConsent.rejectNonEssential();
            });
        }
        if (settingsBtn) {
            settingsBtn.addEventListener('click', function () {
                var url = settingsBtn.getAttribute('data-url') || '/cookie-settings';
                window.location.href = url;
            });
        }
    });
})();
