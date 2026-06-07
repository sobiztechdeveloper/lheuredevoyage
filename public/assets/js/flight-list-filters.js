(function () {
    'use strict';

    function getSortValue() {
        var sortEl = document.getElementById('flight-sort');
        if (!sortEl) {
            return 'price_asc';
        }
        if (typeof jQuery !== 'undefined' && jQuery(sortEl).next('.nice-select').length) {
            return sortEl.value || 'price_asc';
        }
        return sortEl.value || 'price_asc';
    }

    function collectPanelParams(form, params) {
        [
            'flight_search',
            'from-destination',
            'to-destination',
            'journey-date',
            'return-date',
            'flight-type',
            'adult',
            'children',
            'infant',
            'cabin_class',
            'from_departure_id',
            'to_arrival_id'
        ].forEach(function (name) {
            var input = form.querySelector('[name="' + name + '"]:checked')
                || form.querySelector('[name="' + name + '"]');

            if (input) {
                params.set(name, input.value || '');
            }
        });
    }

    function collectFilterParams() {
        var form = document.getElementById('flight-results-filters');
        var params = new URLSearchParams();

        if (!form) {
            return params;
        }

        collectPanelParams(form, params);

        form.querySelectorAll('input[type="checkbox"].flight-list-filter:checked').forEach(function (checkbox) {
            params.append(checkbox.name, checkbox.value);
        });

        var minInput = document.getElementById('filter-price-min');
        var maxInput = document.getElementById('filter-price-max');
        if (minInput && minInput.value !== '') {
            params.set('price_min', minInput.value);
        }
        if (maxInput && maxInput.value !== '') {
            params.set('price_max', maxInput.value);
        }

        params.set('sort', getSortValue());

        return params;
    }

    function applyFlightFilters() {
        var form = document.getElementById('flight-results-filters');
        if (!form) {
            return;
        }

        var sortHidden = document.getElementById('filter-sort');
        if (sortHidden) {
            sortHidden.value = getSortValue();
        }

        var params = collectFilterParams();
        var baseUrl = (form.getAttribute('action') || window.location.pathname).split('?')[0];
        var query = params.toString();

        window.location.href = query ? baseUrl + '?' + query : baseUrl;
    }

    function clearFlightFilters() {
        var form = document.getElementById('flight-results-filters');
        if (!form) {
            window.location.href = window.location.pathname;
            return;
        }

        var baseUrl = (form.getAttribute('action') || window.location.pathname).split('?')[0];
        var params = new URLSearchParams();

        collectPanelParams(form, params);

        var query = params.toString();
        window.location.href = query ? baseUrl + '?' + query : baseUrl;
    }

    function initPriceSlider() {
        if (typeof jQuery === 'undefined' || !jQuery.fn.slider) {
            return;
        }

        var priceRange = document.getElementById('priceRange1');
        var minInput = document.getElementById('filter-price-min');
        var maxInput = document.getElementById('filter-price-max');
        var sliderEl = jQuery('#price-range1');

        if (!priceRange || !minInput || !maxInput || !sliderEl.length) {
            return;
        }

        var currencyPrefix = priceRange.dataset.currencyPrefix || '$';
        var min = parseInt(priceRange.dataset.min || '0', 10);
        var max = parseInt(priceRange.dataset.max || '1000', 10);
        if (max <= min) {
            max = min + 100;
        }

        var currentMin = parseInt(priceRange.dataset.currentMin || min, 10);
        var currentMax = parseInt(priceRange.dataset.currentMax || max, 10);

        if (sliderEl.hasClass('ui-slider')) {
            sliderEl.slider('destroy');
        }

        sliderEl.slider({
            range: true,
            min: min,
            max: max,
            values: [currentMin, currentMax],
            slide: function (event, ui) {
                jQuery('#priceRange1').val(currencyPrefix + ui.values[0] + ' - ' + currencyPrefix + ui.values[1]);
                minInput.value = ui.values[0];
                maxInput.value = ui.values[1];
            },
        });

        jQuery('#priceRange1').val(currencyPrefix + currentMin + ' - ' + currencyPrefix + currentMax);
        minInput.value = currentMin;
        maxInput.value = currentMax;

        if (window.location.search.indexOf('price_min=') === -1) {
            minInput.value = '';
            maxInput.value = '';
        }
    }

    function initSortDropdown() {
        var sortEl = document.getElementById('flight-sort');
        if (!sortEl) {
            return;
        }

        if (typeof jQuery !== 'undefined' && jQuery.fn.niceSelect && jQuery(sortEl).next('.nice-select').length) {
            jQuery(sortEl).niceSelect('update');
        }

        var handler = function () {
            var sortHidden = document.getElementById('filter-sort');
            if (sortHidden) {
                sortHidden.value = getSortValue();
            }
            applyFlightFilters();
        };

        if (typeof jQuery !== 'undefined') {
            jQuery(sortEl).off('change.flightSort').on('change.flightSort', handler);
        } else {
            sortEl.removeEventListener('change', handler);
            sortEl.addEventListener('change', handler);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var applyBtn = document.getElementById('flight-apply-filters');
        var clearBtn = document.getElementById('flight-clear-filters');

        if (applyBtn) {
            applyBtn.addEventListener('click', function (e) {
                e.preventDefault();
                applyFlightFilters();
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function (e) {
                e.preventDefault();
                clearFlightFilters();
            });
        }

        document.querySelectorAll('.flight-list-cabin').forEach(function (radio) {
            radio.addEventListener('change', function () {
                if (this.checked) {
                    applyFlightFilters();
                }
            });
        });

        initPriceSlider();
        initSortDropdown();
    });
})();
