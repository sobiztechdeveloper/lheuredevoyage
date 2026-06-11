(function () {
    'use strict';

    var TOTAL_STEPS = 6;

    function getCsrfToken() {
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function initDatePickers(root) {
        if (typeof jQuery === 'undefined' || !jQuery.fn.datepicker) {
            return;
        }

        jQuery(root).find('.date-picker').each(function () {
            var $input = jQuery(this);
            if ($input.hasClass('hasDatepicker')) {
                return;
            }

            $input.datepicker({
                dateFormat: 'dd/mm/yy',
                minDate: 0,
                onSelect: function (dateText) {
                    $input.val(dateText);
                },
            });
        });
    }

    function getMultiselectOptionCheckboxes(multiselectEl) {
        return multiselectEl.querySelectorAll('.hpr-ms-options input[type="checkbox"]:not(.hpr-ms-select-all-input)');
    }

    function updateSelectAllState(multiselectEl) {
        var selectAll = multiselectEl.querySelector('.hpr-ms-select-all-input');

        if (!selectAll) {
            return;
        }

        var options = getMultiselectOptionCheckboxes(multiselectEl);
        var checkedCount = 0;

        options.forEach(function (checkbox) {
            if (checkbox.checked) {
                checkedCount++;
            }
        });

        if (!options.length || checkedCount === 0) {
            selectAll.checked = false;
            selectAll.indeterminate = false;
        } else if (checkedCount === options.length) {
            selectAll.checked = true;
            selectAll.indeterminate = false;
        } else {
            selectAll.checked = false;
            selectAll.indeterminate = true;
        }
    }

    function updateMultiselectSummary(multiselectEl) {
        var summary = multiselectEl.querySelector('.hpr-ms-summary');
        var checked = multiselectEl.querySelectorAll('.hpr-ms-options input[type="checkbox"]:checked:not(.hpr-ms-select-all-input)');

        if (!summary) {
            return;
        }

        if (!checked.length) {
            summary.textContent = summary.getAttribute('data-placeholder') || 'Not specified';
            summary.classList.remove('has-selection');
            updateSelectAllState(multiselectEl);
            return;
        }

        if (checked.length === 1) {
            summary.textContent = checked[0].getAttribute('data-label') || checked[0].value;
        } else {
            summary.textContent = checked.length + ' selected';
        }

        summary.classList.add('has-selection');
        updateSelectAllState(multiselectEl);
    }

    function initMultiselects(root) {
        root.querySelectorAll('[data-hpr-multiselect]').forEach(function (multiselectEl) {
            var summary = multiselectEl.querySelector('.hpr-ms-summary');
            if (summary && !summary.getAttribute('data-placeholder')) {
                summary.setAttribute('data-placeholder', summary.textContent.trim());
            }

            var selectAllInput = multiselectEl.querySelector('.hpr-ms-select-all-input');

            if (selectAllInput) {
                selectAllInput.addEventListener('change', function () {
                    var shouldCheck = selectAllInput.checked;

                    getMultiselectOptionCheckboxes(multiselectEl).forEach(function (checkbox) {
                        checkbox.checked = shouldCheck;
                    });

                    selectAllInput.indeterminate = false;
                    updateMultiselectSummary(multiselectEl);
                });
            }

            getMultiselectOptionCheckboxes(multiselectEl).forEach(function (checkbox) {
                checkbox.addEventListener('change', function () {
                    updateMultiselectSummary(multiselectEl);
                });
            });

            var searchInput = multiselectEl.querySelector('.hpr-ms-search');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    var query = searchInput.value.trim().toLowerCase();
                    multiselectEl.querySelectorAll('.hpr-ms-option:not(.hpr-ms-select-all)').forEach(function (option) {
                        var label = option.querySelector('.hpr-ms-option-label');
                        var text = label ? label.textContent.toLowerCase() : '';
                        option.classList.toggle('d-none', query !== '' && text.indexOf(query) === -1);
                    });
                });

                searchInput.addEventListener('click', function (event) {
                    event.stopPropagation();
                });
            }

            updateMultiselectSummary(multiselectEl);
        });
    }

    function resetMultiselects(root) {
        root.querySelectorAll('[data-hpr-multiselect]').forEach(function (multiselectEl) {
            var selectAllInput = multiselectEl.querySelector('.hpr-ms-select-all-input');

            if (selectAllInput) {
                selectAllInput.checked = false;
                selectAllInput.indeterminate = false;
            }

            getMultiselectOptionCheckboxes(multiselectEl).forEach(function (checkbox) {
                checkbox.checked = false;
            });

            var searchInput = multiselectEl.querySelector('.hpr-ms-search');
            if (searchInput) {
                searchInput.value = '';
            }

            multiselectEl.querySelectorAll('.hpr-ms-option:not(.hpr-ms-select-all)').forEach(function (option) {
                option.classList.remove('d-none');
            });

            updateMultiselectSummary(multiselectEl);
        });
    }

    function renderChildAges(container, count) {
        container.innerHTML = '';

        if (count <= 0) {
            container.classList.add('d-none');
            return;
        }

        container.classList.remove('d-none');

        for (var i = 1; i <= count; i++) {
            var field = document.createElement('div');
            field.className = 'hpr-child-age-field';

            var options = '<option value=""></option>';
            for (var age = 0; age <= 17; age++) {
                options += '<option value="' + age + '">' + age + '</option>';
            }

            field.innerHTML =
                '<label class="form-label">Age ' + i + '</label>' +
                '<select class="form-select form-select-sm" name="child_ages[]">' + options + '</select>';
            container.appendChild(field);
        }
    }

    function getCheckedLabels(root, selector) {
        return Array.prototype.slice.call(root.querySelectorAll(selector))
            .filter(function (input) { return input.checked; })
            .map(function (input) { return input.getAttribute('data-label') || input.value; });
    }

    function buildSupplementalNotes(root) {
        var lines = [];
        var holidayTypes = getCheckedLabels(root, 'input[id^="hpr-holiday-type-"][type="checkbox"]');
        var priorityEl = root.querySelector('#hpr-priority');
        var contactEl = root.querySelector('input[name="hpr_preferred_contact"]:checked');
        var gdprEl = root.querySelector('#hpr-gdpr-consent');

        if (holidayTypes.length) {
            lines.push('Holiday Type: ' + holidayTypes.join(', '));
        }

        if (priorityEl && priorityEl.value) {
            var priorityLabel = priorityEl.options[priorityEl.selectedIndex].text;
            lines.push('Priority: ' + priorityLabel);
        }

        if (contactEl) {
            lines.push('Preferred Contact Method: ' + contactEl.value);
        }

        if (gdprEl && gdprEl.checked) {
            lines.push('GDPR Consent: ' + new Date().toISOString());
        }

        return lines.join('\n');
    }

    function showAlert(alertEl, message, type) {
        if (!alertEl) {
            return;
        }

        alertEl.className = 'alert alert-' + type + ' py-2 mb-2';
        alertEl.textContent = message;
        alertEl.classList.remove('d-none');
        alertEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideAlert(alertEl) {
        if (!alertEl) {
            return;
        }

        alertEl.classList.add('d-none');
        alertEl.textContent = '';
    }

    function validateStep(stepEl) {
        if (!stepEl) {
            return true;
        }

        var fields = stepEl.querySelectorAll('input, select, textarea');
        var valid = true;

        fields.forEach(function (field) {
            if (field.disabled || field.type === 'hidden') {
                return;
            }

            if (!field.checkValidity()) {
                valid = false;
                field.reportValidity();
            }
        });

        return valid;
    }

    function validateAllSteps(wizard, navigation) {
        for (var step = 1; step <= TOTAL_STEPS; step++) {
            navigation.goToStep(step);

            var stepEl = wizard.querySelector('.hpr-wizard-step[data-step="' + step + '"]');
            if (!validateStep(stepEl)) {
                return false;
            }
        }

        return true;
    }

    function parseJsonResponse(response, labels) {
        return response.text().then(function (text) {
            var payload = {};

            if (text) {
                try {
                    payload = JSON.parse(text);
                } catch (error) {
                    throw {
                        message: labels.error + ' (HTTP ' + response.status + ')',
                    };
                }
            }

            if (!response.ok) {
                throw payload;
            }

            return payload;
        });
    }

    function initWizardNavigation(wizard, labels) {
        var currentStep = 1;
        var steps = wizard.querySelectorAll('.hpr-wizard-step');
        var progressSteps = wizard.querySelectorAll('.hpr-progress-step');
        var stepIndicator = wizard.querySelector('#hpr-step-indicator');
        var stepName = wizard.querySelector('#hpr-step-name');
        var progressBar = wizard.querySelector('#hpr-progress-bar');
        var progressTrack = wizard.querySelector('.hpr-progress-track');
        var backBtn = wizard.querySelector('#hpr-back-btn');
        var nextBtn = wizard.querySelector('#hpr-next-btn');
        var submitBtn = wizard.querySelector('#hpr-submit-btn');

        function getStepLabel(stepNumber) {
            var progressStep = wizard.querySelector('.hpr-progress-step[data-step="' + stepNumber + '"] .hpr-progress-step-label');
            return progressStep ? progressStep.textContent.trim() : '';
        }

        function updateProgressUI() {
            steps.forEach(function (step) {
                var stepNumber = parseInt(step.getAttribute('data-step'), 10);
                step.classList.toggle('d-none', stepNumber !== currentStep);
            });

            progressSteps.forEach(function (item) {
                var stepNumber = parseInt(item.getAttribute('data-step'), 10);
                item.classList.remove('is-active', 'is-complete');

                if (stepNumber < currentStep) {
                    item.classList.add('is-complete');
                } else if (stepNumber === currentStep) {
                    item.classList.add('is-active');
                }
            });

            if (stepIndicator) {
                stepIndicator.textContent = 'Step ' + currentStep + ' of ' + TOTAL_STEPS;
            }

            if (stepName) {
                stepName.textContent = getStepLabel(currentStep);
            }

            if (progressBar) {
                progressBar.style.width = ((currentStep / TOTAL_STEPS) * 100) + '%';
            }

            if (progressTrack) {
                progressTrack.setAttribute('aria-valuenow', String(currentStep));
            }

            if (backBtn) {
                backBtn.disabled = currentStep === 1;
            }

            if (nextBtn && submitBtn) {
                var isLast = currentStep === TOTAL_STEPS;
                nextBtn.classList.toggle('d-none', isLast);
                submitBtn.classList.toggle('d-none', !isLast);
            }
        }

        function goToStep(stepNumber) {
            currentStep = Math.max(1, Math.min(TOTAL_STEPS, stepNumber));
            updateProgressUI();
            hideAlert(wizard.querySelector('#holidayPackageRequestAlert'));
            wizard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }

        if (backBtn) {
            backBtn.addEventListener('click', function () {
                goToStep(currentStep - 1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                var activeStep = wizard.querySelector('.hpr-wizard-step[data-step="' + currentStep + '"]');
                if (!validateStep(activeStep)) {
                    return;
                }

                goToStep(currentStep + 1);
            });
        }

        updateProgressUI();

        return {
            reset: function () {
                currentStep = 1;
                updateProgressUI();
            },
            goToStep: goToStep,
        };
    }

    function initHolidayPackageRequestForm(wizard, labels, wrap) {
        var childrenInput = wizard.querySelector('#hpr-children');
        var childAgesContainer = wizard.querySelector('#hpr-child-ages');
        var form = wizard.querySelector('#holidayPackageRequestForm');
        var alertEl = wizard.querySelector('#holidayPackageRequestAlert');
        var submitBtn = wizard.querySelector('#hpr-submit-btn');
        var gdprCheckbox = wizard.querySelector('#hpr-gdpr-consent');
        var notesField = wizard.querySelector('#hpr-additional-notes');
        var navigation = initWizardNavigation(wizard, labels);

        initMultiselects(wizard);

        if (childrenInput && childAgesContainer) {
            var updateAges = function () {
                var count = parseInt(childrenInput.value || '0', 10);
                renderChildAges(childAgesContainer, Math.max(0, Math.min(count, 10)));
            };

            childrenInput.addEventListener('input', updateAges);
            childrenInput.addEventListener('change', updateAges);
            updateAges();
        }

        function submitHolidayPackageRequest() {
            if (!validateAllSteps(wizard, navigation)) {
                return;
            }

            if (gdprCheckbox && !gdprCheckbox.checked) {
                navigation.goToStep(TOTAL_STEPS);
                gdprCheckbox.reportValidity();
                showAlert(alertEl, 'Please accept the privacy consent to submit your request.', 'danger');
                return;
            }

            if (submitBtn) {
                submitBtn.disabled = true;
            }

            var supplemental = buildSupplementalNotes(wizard);
            var originalNotes = notesField ? notesField.value.trim() : '';
            var mergedNotes = supplemental;

            if (originalNotes) {
                mergedNotes = mergedNotes ? mergedNotes + '\n\n' + originalNotes : originalNotes;
            }

            if (notesField) {
                notesField.value = mergedNotes;
            }

            var formData = new FormData(form);

            fetch(form.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: formData,
            })
                .then(function (response) {
                    return parseJsonResponse(response, labels);
                })
                .then(function (payload) {
                    showAlert(alertEl, payload.message, 'success');
                    form.reset();

                    if (notesField) {
                        notesField.value = '';
                    }

                    resetMultiselects(wizard);
                    navigation.reset();

                    if (childrenInput) {
                        childrenInput.dispatchEvent(new Event('change'));
                    }

                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }

                    if (wrap) {
                        wrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }
                })
                .catch(function (error) {
                    if (notesField) {
                        notesField.value = originalNotes;
                    }

                    var message = labels.error;

                    if (error && error.errors) {
                        message = Object.values(error.errors).flat().join(' ');
                    } else if (error && error.message) {
                        message = error.message;
                    }

                    showAlert(alertEl, message, 'danger');
                    navigation.goToStep(TOTAL_STEPS);

                    if (submitBtn) {
                        submitBtn.disabled = false;
                    }
                });
        }

        if (form) {
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                submitHolidayPackageRequest();
            });
        }

        if (submitBtn) {
            submitBtn.addEventListener('click', function (event) {
                event.preventDefault();
                submitHolidayPackageRequest();
            });
        }

        initDatePickers(wizard);
    }

    document.addEventListener('DOMContentLoaded', function () {
        var wrap = document.getElementById('holiday-package-request-wizard-wrap');
        var wizard = document.getElementById('holidayPackageRequestWizard');

        if (!wrap || !wizard) {
            return;
        }

        var labels = {
            childAge: wrap.getAttribute('data-child-age-label') || 'Child :number age',
            error: wrap.getAttribute('data-error-message') || 'Please check the form and try again.',
        };

        initHolidayPackageRequestForm(wizard, labels, wrap);
    });
})();
