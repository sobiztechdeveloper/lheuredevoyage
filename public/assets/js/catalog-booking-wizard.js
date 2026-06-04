(function () {
    'use strict';

    var form = document.getElementById('cbw-form');
    if (!form) {
        return;
    }

    var panels = form.querySelectorAll('.fbw-step-panel');
    var progressLabels = document.querySelectorAll('[data-cbw-progress]');
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
        var valid = true;

        panel.querySelectorAll('[required]').forEach(function (field) {
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
        progressLabels.forEach(function (label) {
            var s = parseInt(label.getAttribute('data-cbw-progress'), 10);
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
        if (step === totalSteps) {
            buildReview();
        }
    }

    function gatherText(name) {
        var field = form.querySelector('[name="' + name + '"]');
        return field ? String(field.value || '').trim() : '';
    }

    function buildReview() {
        var el = document.getElementById('cbw-review-content');
        if (!el || !window.catalogBookingWizardSummary) {
            return;
        }
        var summary = window.catalogBookingWizardSummary;
        var lines = [];
        lines.push('<p><strong>Product:</strong> ' + (summary.product || '') + '</p>');
        if (summary.location) {
            lines.push('<p><strong>Location:</strong> ' + summary.location + '</p>');
        }

        ['departure_date', 'pickup_date', 'travel_start'].forEach(function (key) {
            var value = gatherText(key);
            if (value) {
                lines.push('<p><strong>' + key.replace('_', ' ').replace(/\b\w/g, function (l) { return l.toUpperCase(); }) + ':</strong> ' + value + '</p>');
            }
        });
        ['return_date', 'travel_end'].forEach(function (key) {
            var value = gatherText(key);
            if (value) {
                lines.push('<p><strong>' + key.replace('_', ' ').replace(/\b\w/g, function (l) { return l.toUpperCase(); }) + ':</strong> ' + value + '</p>');
            }
        });
        ['contact_name', 'contact_email', 'contact_phone'].forEach(function (key) {
            var value = gatherText(key);
            if (value) {
                lines.push('<p><strong>' + key.replace('contact_', 'Contact ').replace('_', ' ').replace(/\b\w/g, function (l) { return l.toUpperCase(); }) + ':</strong> ' + value + '</p>');
            }
        });
        lines.push('<p class="mb-0"><strong>Estimated total:</strong> ' + (summary.estimated || '') + '</p>');
        el.innerHTML = lines.join('');
    }

    form.querySelectorAll('[data-cbw-next]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!validatePanel(currentStep)) {
                return;
            }
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        });
    });

    form.querySelectorAll('[data-cbw-prev]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        });
    });

    form.addEventListener('submit', function (e) {
        if (!validatePanel(totalSteps)) {
            e.preventDefault();
            showStep(totalSteps);
        }
    });

    showStep(1);
})();
