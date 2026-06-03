(function () {
    'use strict';

    var form = document.getElementById('fbw-form');
    if (!form) {
        return;
    }

    var panels = form.querySelectorAll('.fbw-step-panel');
    var progressItems = document.querySelectorAll('.fbw-progress [data-step]');
    var currentStep = 1;
    var totalSteps = panels.length;
    var summary = window.fbwFlightSummary || {};
    var seatLabels = window.fbwSeatLabels || {};
    var mealLabels = window.fbwMealLabels || {};
    var assistanceLabels = window.fbwAssistanceLabels || {};

    function showStep(step) {
        currentStep = step;
        panels.forEach(function (panel) {
            panel.classList.toggle('active', parseInt(panel.dataset.step, 10) === step);
        });
        progressItems.forEach(function (item) {
            var itemStep = parseInt(item.dataset.step, 10);
            item.classList.toggle('active', itemStep === step);
            item.classList.toggle('completed', itemStep < step);
        });
        window.scrollTo({ top: form.offsetTop - 80, behavior: 'smooth' });
        if (step === 3) {
            syncContactName();
        }
    }

    function clearFieldErrors(panel) {
        panel.querySelectorAll('.is-invalid').forEach(function (el) {
            el.classList.remove('is-invalid');
        });
        panel.querySelectorAll('.fbw-invalid-inline').forEach(function (el) {
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

        if (step === 2) {
            var valid = true;
            var radio = form.querySelector('input[name="contact_passenger_index"]:checked');
            if (!radio) {
                valid = false;
                var radiosWrap = document.getElementById('fbw-contact-passenger-radios');
                if (radiosWrap) {
                    var hint = document.createElement('span');
                    hint.className = 'fbw-invalid-inline d-block';
                    hint.textContent = 'Please select a booking contact passenger.';
                    radiosWrap.appendChild(hint);
                }
            }
            panel.querySelectorAll('[required]').forEach(function (field) {
                if (field.type === 'file') {
                    return;
                }
                if (!field.value || (field.type === 'checkbox' && !field.checked)) {
                    valid = false;
                    showFieldError(field, 'This field is required.');
                }
            });
            return valid;
        }

        if (step === 3) {
            syncContactName();
            var ok = true;
            ['contact_name', 'email', 'phone'].forEach(function (name) {
                var field = panel.querySelector('[name="' + name + '"]');
                if (field && !field.value.trim()) {
                    ok = false;
                    showFieldError(field, 'This field is required.');
                }
            });
            var emailField = panel.querySelector('[name="email"]');
            if (emailField && emailField.value && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailField.value)) {
                ok = false;
                showFieldError(emailField, 'Please enter a valid email address.');
            }
            return ok;
        }

        if (step === 5) {
            var accept = panel.querySelector('[name="accept_conditions"]');
            if (accept && !accept.checked) {
                showFieldError(accept, 'Please accept the booking conditions.');
                return false;
            }
            buildReview();
        }

        return true;
    }

    function getPassengerBlocks() {
        var container = document.getElementById('fbw-passengers-container');
        return container ? container.querySelectorAll('.fbw-passenger-block') : [];
    }

    function syncContactName() {
        var contactNameInput = document.getElementById('fbw-contact-name');
        var radio = form.querySelector('input[name="contact_passenger_index"]:checked');
        if (!contactNameInput || !radio) {
            return;
        }
        var index = radio.value;
        var block = form.querySelector('.fbw-passenger-block[data-passenger-index="' + index + '"]');
        if (!block) {
            return;
        }
        var title = block.querySelector('[name="passengers[' + index + '][title]"]');
        var first = block.querySelector('[name="passengers[' + index + '][first_name]"]');
        var last = block.querySelector('[name="passengers[' + index + '][last_name]"]');
        if (first && last) {
            contactNameInput.value = [title ? title.value : '', first.value, last.value].filter(Boolean).join(' ').trim();
        }
    }

    function rebuildContactPassengerRadios() {
        var wrap = document.getElementById('fbw-contact-passenger-radios');
        if (!wrap) {
            return;
        }
        var selected = form.querySelector('input[name="contact_passenger_index"]:checked');
        var selectedValue = selected ? selected.value : null;
        wrap.innerHTML = '';
        getPassengerBlocks().forEach(function (block, i) {
            var index = block.getAttribute('data-passenger-index');
            var labelEl = block.querySelector('.fbw-passenger-heading, .fbw-extra-passenger-label');
            var labelText = block.getAttribute('data-passenger-label') || (labelEl ? labelEl.textContent.trim() : 'Passenger ' + (i + 1));
            var div = document.createElement('div');
            div.className = 'form-check';
            var checked = selectedValue === index || (!selectedValue && i === 0);
            div.innerHTML =
                '<input class="form-check-input fbw-contact-passenger-radio" type="radio" name="contact_passenger_index" id="contact-passenger-' + index + '" value="' + index + '"' + (checked ? ' checked' : '') + '>' +
                '<label class="form-check-label" for="contact-passenger-' + index + '">' + labelText + '</label>';
            wrap.appendChild(div);
        });
        wrap.querySelectorAll('.fbw-contact-passenger-radio').forEach(function (radio) {
            radio.addEventListener('change', syncContactName);
        });
        syncContactName();
    }

    function formatDate(value) {
        if (!value) {
            return '—';
        }
        try {
            var d = new Date(value);
            if (!isNaN(d.getTime())) {
                return d.toLocaleDateString(undefined, { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            }
        } catch (e) {}
        return value;
    }

    function buildReview() {
        var reviewFlight = document.getElementById('fbw-review-flight');
        var reviewPassengers = document.getElementById('fbw-review-passengers');
        var reviewContact = document.getElementById('fbw-review-contact');
        var reviewPreferences = document.getElementById('fbw-review-preferences');

        if (reviewFlight && summary) {
            var pax = (summary.adults || 0) + ' Adult(s), ' + (summary.children || 0) + ' Child(ren), ' + (summary.infants || 0) + ' Infant(s)';
            reviewFlight.innerHTML =
                '<ul class="fbw-review-list">' +
                '<li><strong>Route:</strong> ' + summary.from + ' (' + summary.from_code + ') → ' + summary.to + ' (' + summary.to_code + ')</li>' +
                '<li><strong>Depart:</strong> ' + formatDate(summary.departure_date) + '</li>' +
                (summary.return_date ? '<li><strong>Return:</strong> ' + formatDate(summary.return_date) + '</li>' : '') +
                '<li><strong>Passengers:</strong> ' + pax + '</li>' +
                '<li><strong>Cabin:</strong> ' + summary.cabin_class + '</li>' +
                '<li><strong>Estimated Fare:</strong> ' + (summary.currency || 'USD') + ' ' + Number(summary.estimated_fare || 0).toLocaleString() + '</li>' +
                '</ul>';
        }

        if (reviewPassengers) {
            var html = '<div class="table-responsive"><table class="table table-sm"><thead><tr><th>Name</th><th>Passport</th><th>Nationality</th></tr></thead><tbody>';
            getPassengerBlocks().forEach(function (block) {
                var index = block.getAttribute('data-passenger-index');
                var first = block.querySelector('[name="passengers[' + index + '][first_name]"]');
                var last = block.querySelector('[name="passengers[' + index + '][last_name]"]');
                var title = block.querySelector('[name="passengers[' + index + '][title]"]');
                var passport = block.querySelector('[name="passengers[' + index + '][passport_number]"]');
                var nationality = block.querySelector('[name="passengers[' + index + '][nationality]"]');
                var type = block.querySelector('[name="passengers[' + index + '][passenger_type]"]');
                if (first && last) {
                    html += '<tr><td>' + (title && title.value ? title.value + ' ' : '') + first.value + ' ' + last.value +
                        ' <small class="text-muted">(' + (type ? type.value : '') + ')</small></td>' +
                        '<td>' + (passport ? passport.value : '—') + '</td>' +
                        '<td>' + (nationality ? nationality.value : '—') + '</td></tr>';
                }
            });
            html += '</tbody></table></div>';
            reviewPassengers.innerHTML = html;
        }

        if (reviewContact) {
            reviewContact.innerHTML =
                '<ul class="fbw-review-list">' +
                '<li><strong>Name:</strong> ' + (form.contact_name ? form.contact_name.value : '') + '</li>' +
                '<li><strong>Email:</strong> ' + form.email.value + '</li>' +
                '<li><strong>Phone:</strong> ' + form.phone.value + '</li>' +
                (form.whatsapp.value ? '<li><strong>WhatsApp:</strong> ' + form.whatsapp.value + '</li>' : '') +
                (form.country.value ? '<li><strong>Country:</strong> ' + form.country.value + '</li>' : '') +
                '</ul>';
        }

        if (reviewPreferences) {
            var seat = form.seat_preference ? form.seat_preference.value : 'no_preference';
            var meal = form.meal_preference ? form.meal_preference.value : 'no_preference';
            var prefHtml = '<ul class="fbw-review-list">';
            if (form.preferred_airline && form.preferred_airline.value) {
                prefHtml += '<li><strong>Preferred Airline:</strong> ' + form.preferred_airline.value + '</li>';
            }
            prefHtml += '<li><strong>Seat:</strong> ' + (seatLabels[seat] || seat) + '</li>';
            prefHtml += '<li><strong>Meal:</strong> ' + (mealLabels[meal] || meal) + '</li>';
            var assist = [];
            form.querySelectorAll('[name^="special_assistance"]').forEach(function (cb) {
                if (cb.checked) {
                    var key = cb.name.match(/\[([^\]]+)\]/);
                    assist.push(assistanceLabels[key ? key[1] : ''] || cb.id);
                }
            });
            prefHtml += '<li><strong>Assistance:</strong> ' + (assist.length ? assist.join(', ') : 'None') + '</li>';
            if (form.special_requests && form.special_requests.value.trim()) {
                prefHtml += '<li><strong>Notes:</strong> ' + form.special_requests.value.trim() + '</li>';
            }
            prefHtml += '</ul>';
            reviewPreferences.innerHTML = prefHtml;
        }
    }

    form.addEventListener('change', function (e) {
        if (e.target && e.target.name === 'contact_passenger_index') {
            syncContactName();
        }
    });

    form.querySelectorAll('[data-fbw-next]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!validatePanel(currentStep)) {
                return;
            }
            if (currentStep < totalSteps) {
                showStep(currentStep + 1);
            }
        });
    });

    form.querySelectorAll('[data-fbw-prev]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (currentStep > 1) {
                showStep(currentStep - 1);
            }
        });
    });

    var passengersContainer = document.getElementById('fbw-passengers-container');
    var extraPassengersWrap = document.getElementById('fbw-extra-passengers');
    var addPassengerBtn = document.getElementById('fbw-add-passenger');
    var extraTpl = document.getElementById('fbw-extra-passenger-tpl');

    function getNextPassengerIndex() {
        var maxIndex = -1;
        getPassengerBlocks().forEach(function (block) {
            var idx = parseInt(block.getAttribute('data-passenger-index'), 10);
            if (!isNaN(idx) && idx > maxIndex) {
                maxIndex = idx;
            }
        });
        return maxIndex + 1;
    }

    function getExtraPassengerCount() {
        return passengersContainer
            ? passengersContainer.querySelectorAll('[data-extra-passenger="1"]').length
            : 0;
    }

    function updateAddPassengerButton() {
        if (!addPassengerBtn || !passengersContainer) {
            return;
        }
        var max = parseInt(passengersContainer.getAttribute('data-max-passengers'), 10) || 9;
        addPassengerBtn.disabled = getPassengerBlocks().length >= max;
    }

    function bindRemovePassengerButtons(scope) {
        (scope || passengersContainer).querySelectorAll('.fbw-remove-passenger').forEach(function (btn) {
            if (btn.dataset.bound === '1') {
                return;
            }
            btn.dataset.bound = '1';
            btn.addEventListener('click', function () {
                var block = btn.closest('.fbw-passenger-block');
                if (block && block.getAttribute('data-extra-passenger') === '1') {
                    block.remove();
                    renumberExtraPassengerLabels();
                    rebuildContactPassengerRadios();
                    updateAddPassengerButton();
                }
            });
        });
    }

    function renumberExtraPassengerLabels() {
        var extraIndex = 0;
        passengersContainer.querySelectorAll('[data-extra-passenger="1"]').forEach(function (block) {
            extraIndex++;
            var label = block.querySelector('.fbw-extra-passenger-label');
            var labelText = 'Additional Passenger ' + extraIndex;
            if (label) {
                label.textContent = labelText;
            }
            block.setAttribute('data-passenger-label', labelText);
        });
    }

    function addExtraPassenger() {
        if (!extraTpl || !extraPassengersWrap) {
            return;
        }
        var max = parseInt(passengersContainer.getAttribute('data-max-passengers'), 10) || 9;
        if (getPassengerBlocks().length >= max) {
            return;
        }
        var index = getNextPassengerIndex();
        var extraCount = getExtraPassengerCount() + 1;
        var labelText = 'Additional Passenger ' + extraCount;
        var html = extraTpl.innerHTML.replace(/INDEX/g, String(index));
        extraPassengersWrap.insertAdjacentHTML('beforeend', html);
        var added = extraPassengersWrap.lastElementChild;
        if (added) {
            added.setAttribute('data-passenger-label', labelText);
            var label = added.querySelector('.fbw-extra-passenger-label');
            if (label) {
                label.textContent = labelText;
            }
            bindRemovePassengerButtons(added);
        }
        rebuildContactPassengerRadios();
        updateAddPassengerButton();
    }

    if (addPassengerBtn) {
        addPassengerBtn.addEventListener('click', addExtraPassenger);
        bindRemovePassengerButtons();
        updateAddPassengerButton();
    }

    rebuildContactPassengerRadios();
    showStep(1);
})();
