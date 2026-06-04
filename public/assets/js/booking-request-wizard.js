(function () {
    'use strict';

    var form = document.getElementById('fbw-form')
        || document.getElementById('cbw-form')
        || document.getElementById('hbw-form');

    if (!form) {
        return;
    }

    var panels = form.querySelectorAll('.fbw-step-panel');
    var progressItems = document.querySelectorAll('.fbw-progress [data-step]');
    var currentStep = 1;
    var totalSteps = panels.length;

    function clearFieldErrors(panel) {
        panel.querySelectorAll('.is-invalid').forEach(function (el) {
            el.classList.remove('is-invalid');
        });
        panel.querySelectorAll('.fbw-invalid-inline, .hbw-invalid-inline').forEach(function (el) {
            el.remove();
        });
    }

    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        var hint = document.createElement('span');
        hint.className = 'fbw-invalid-inline';
        hint.textContent = message;
        field.parentNode.appendChild(hint);
    }

    function validatePanel(step) {
        var panel = form.querySelector('.fbw-step-panel[data-step="' + step + '"]');
        if (!panel) {
            return true;
        }

        clearFieldErrors(panel);
        var valid = true;

        panel.querySelectorAll('[required]').forEach(function (field) {
            if (field.type === 'file' || field.type === 'radio') {
                return;
            }
            if (!String(field.value || '').trim()) {
                valid = false;
                showFieldError(field, 'This field is required.');
            }
        });

        panel.querySelectorAll('input[type="email"]').forEach(function (email) {
            if (email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                valid = false;
                showFieldError(email, 'Please enter a valid email address.');
            }
        });

        if (step === totalSteps) {
            var accept = panel.querySelector('[name="accept_conditions"]');
            if (accept && !accept.checked) {
                valid = false;
                showFieldError(accept, 'Please accept the conditions to continue.');
            }
            if (valid) {
                buildReview();
            }
        }

        return valid;
    }

    function updateProgress(step) {
        progressItems.forEach(function (item) {
            var itemStep = parseInt(item.getAttribute('data-step'), 10);
            item.classList.toggle('active', itemStep === step);
            item.classList.toggle('completed', itemStep < step);
        });
    }

    function showStep(step) {
        currentStep = step;
        panels.forEach(function (panel) {
            panel.classList.toggle('active', parseInt(panel.dataset.step, 10) === step);
        });
        updateProgress(step);
        window.scrollTo({ top: form.offsetTop - 80, behavior: 'smooth' });
        if (step === totalSteps) {
            buildReview();
        }
    }

    function gatherText(name) {
        var field = form.querySelector('[name="' + name + '"]');
        return field ? String(field.value || '').trim() : '';
    }

    function buildReview() {
        var el = document.getElementById('fbw-review-content')
            || document.getElementById('cbw-review-content')
            || document.getElementById('hbw-review-content');
        if (!el) {
            return;
        }

        var data = window.bookingWizardReview
            || window.catalogBookingWizardSummary
            || window.hbwSummary
            || {};

        var lines = [];

        if (data.sections && Array.isArray(data.sections)) {
            data.sections.forEach(function (section) {
                lines.push('<div class="fbw-review-section"><h6>' + section.title + '</h6>' + section.html + '</div>');
            });
        } else {
            if (data.product || data.hotel || data.headline) {
                lines.push('<p><strong>Product:</strong> ' + (data.product || data.hotel || data.headline || '') + '</p>');
            }
            if (data.room) {
                lines.push('<p><strong>Room:</strong> ' + data.room + '</p>');
            }
            if (data.location || data.subtitle) {
                lines.push('<p><strong>Location:</strong> ' + (data.location || data.subtitle || '') + '</p>');
            }
            ['departure_date', 'pickup_date', 'travel_start', 'check_in', 'journey-date'].forEach(function (key) {
                var value = gatherText(key) || data[key];
                if (value) {
                    lines.push('<p><strong>' + key.replace(/-/g, ' ') + ':</strong> ' + value + '</p>');
                }
            });
            ['return_date', 'travel_end', 'check_out', 'return-date'].forEach(function (key) {
                var value = gatherText(key) || data[key];
                if (value) {
                    lines.push('<p><strong>' + key.replace(/-/g, ' ') + ':</strong> ' + value + '</p>');
                }
            });
            ['contact_name', 'contact_email', 'contact_phone', 'lead_guest_email'].forEach(function (key) {
                var value = gatherText(key) || data[key];
                if (value) {
                    lines.push('<p><strong>' + key.replace(/_/g, ' ') + ':</strong> ' + value + '</p>');
                }
            });
            if (data.estimated || data.fare) {
                lines.push('<p class="mb-0"><strong>Estimated total:</strong> ' + (data.estimated || data.fare) + '</p>');
            }
        }

        el.innerHTML = lines.join('') || '<p class="text-muted mb-0">Review your details above before submitting.</p>';
    }

    function bindNav(selector, delta) {
        form.querySelectorAll(selector).forEach(function (btn) {
            btn.addEventListener('click', function () {
                if (delta > 0 && !validatePanel(currentStep)) {
                    return;
                }
                var next = currentStep + delta;
                if (next >= 1 && next <= totalSteps) {
                    showStep(next);
                }
            });
        });
    }

    bindNav('[data-fbw-next], [data-cbw-next], [data-hbw-next]', 1);
    bindNav('[data-fbw-prev], [data-cbw-prev], [data-hbw-prev]', -1);

    form.addEventListener('submit', function (e) {
        if (!validatePanel(totalSteps)) {
            e.preventDefault();
            showStep(totalSteps);
        }
    });

    showStep(1);
})();
