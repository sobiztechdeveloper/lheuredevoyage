(function () {
    'use strict';

    var form = document.getElementById('hbw-form');
    if (!form) {
        return;
    }

    var panels = form.querySelectorAll('.fbw-step-panel');
    var progressLabels = document.querySelectorAll('[data-hbw-progress]');
    var currentStep = 1;
    var totalSteps = panels.length;

    function clearFieldErrors(panel) {
        panel.querySelectorAll('.is-invalid').forEach(function (el) {
            el.classList.remove('is-invalid');
        });
        panel.querySelectorAll('.hbw-invalid-inline').forEach(function (el) {
            el.remove();
        });
    }

    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        var hint = document.createElement('span');
        hint.className = 'hbw-invalid-inline fbw-invalid-inline';
        hint.textContent = message;
        field.parentNode.appendChild(hint);
    }

    function validatePanel(step) {
        var panel = form.querySelector('.fbw-step-panel[data-step="' + step + '"]');
        if (!panel) {
            return true;
        }

        clearFieldErrors(panel);

        if (step === 1) {
            return true;
        }

        if (step === 2) {
            var valid = true;
            panel.querySelectorAll('[required]').forEach(function (field) {
                if (!String(field.value || '').trim()) {
                    valid = false;
                    showFieldError(field, 'This field is required.');
                }
            });
            var email = panel.querySelector('[name="lead_guest_email"]');
            if (email && email.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email.value)) {
                valid = false;
                showFieldError(email, 'Please enter a valid email address.');
            }
            return valid;
        }

        if (step === 3) {
            var accept = panel.querySelector('[name="accept_conditions"]');
            if (accept && !accept.checked) {
                showFieldError(accept, 'Please accept the conditions to continue.');
                return false;
            }
            buildReview();
        }

        return true;
    }

    function updateProgress(step) {
        progressLabels.forEach(function (label) {
            var s = parseInt(label.getAttribute('data-hbw-progress'), 10);
            label.classList.toggle('active', s === step);
            label.classList.toggle('completed', s < step);
        });
    }

    function showStep(step) {
        currentStep = step;
        panels.forEach(function (p) {
            p.classList.toggle('active', parseInt(p.dataset.step, 10) === step);
        });
        updateProgress(step);
        window.scrollTo({ top: form.offsetTop - 80, behavior: 'smooth' });
        if (step === 3) {
            buildReview();
        }
    }

    function buildReview() {
        var el = document.getElementById('hbw-review-content');
        if (!el || !window.hbwSummary) {
            return;
        }
        var s = window.hbwSummary;
        var first = form.querySelector('[name="lead_guest_first_name"]');
        var last = form.querySelector('[name="lead_guest_last_name"]');
        var email = form.querySelector('[name="lead_guest_email"]');
        var phone = form.querySelector('[name="lead_guest_phone"]');
        var country = form.querySelector('[name="country"]');
        var bookingFor = form.querySelector('[name="booking_for"]:checked');

        el.innerHTML =
            '<p><strong>Hotel:</strong> ' + s.hotel + ' — ' + s.location + '</p>' +
            '<p><strong>Room:</strong> ' + s.room + '</p>' +
            '<p><strong>Stay:</strong> ' + s.check_in + ' → ' + s.check_out + ' (' + s.nights + ' nights, ' + s.rooms + ' room(s))</p>' +
            '<p><strong>Occupancy:</strong> ' + s.occupancy + '</p>' +
            '<hr>' +
            '<p><strong>Booking contact:</strong> ' + (first?.value || '') + ' ' + (last?.value || '') + '</p>' +
            '<p><strong>Email:</strong> ' + (email?.value || '') + '</p>' +
            '<p><strong>Phone:</strong> ' + (phone?.value || '') + '</p>' +
            (country?.value ? '<p><strong>Country:</strong> ' + country.value + '</p>' : '') +
            (bookingFor ? '<p><strong>Booking for:</strong> ' + (bookingFor.value === 'main_guest' ? 'Main guest' : 'Someone else') + '</p>' : '') +
            '<p class="mb-0"><strong>Estimated total:</strong> ' + s.estimated + '</p>';
    }

    form.querySelectorAll('[data-hbw-next]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!validatePanel(currentStep)) {
                return;
            }
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        });
    });

    form.querySelectorAll('[data-hbw-prev]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        });
    });

    form.addEventListener('submit', function (e) {
        if (!validatePanel(3)) {
            e.preventDefault();
            showStep(3);
        }
    });

    showStep(1);
})();
