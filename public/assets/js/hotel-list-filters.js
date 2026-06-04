(function () {
    'use strict';

    function getSortValue() {
        var sortEl = document.getElementById('hotel-sort');
        if (!sortEl) {
            return 'default';
        }
        if (typeof jQuery !== 'undefined' && jQuery(sortEl).next('.nice-select').length) {
            return sortEl.value || 'default';
        }
        return sortEl.value || 'default';
    }

    function collectFilterParams() {
        var form = document.getElementById('hotel-results-filters');
        var params = new URLSearchParams();

        if (!form) {
            return params;
        }

        var searchId = form.querySelector('input[name="hotel_search"]');
        if (searchId && searchId.value) {
            params.set('hotel_search', searchId.value);
        }

        form.querySelectorAll('input[type="checkbox"].hotel-list-filter:checked').forEach(function (checkbox) {
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

    function applyHotelFilters() {
        var form = document.getElementById('hotel-results-filters');
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

    function clearHotelFilters() {
        var form = document.getElementById('hotel-results-filters');
        if (!form) {
            window.location.href = window.location.pathname;
            return;
        }

        var searchId = form.querySelector('input[name="hotel_search"]');
        var baseUrl = (form.getAttribute('action') || window.location.pathname).split('?')[0];

        if (searchId && searchId.value) {
            window.location.href = baseUrl + '?hotel_search=' + encodeURIComponent(searchId.value);
        } else {
            window.location.href = baseUrl;
        }
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
                jQuery('#priceRange1').val('$' + ui.values[0] + ' - $' + ui.values[1]);
                minInput.value = ui.values[0];
                maxInput.value = ui.values[1];
            },
        });

        jQuery('#priceRange1').val('$' + currentMin + ' - $' + currentMax);
        minInput.value = currentMin;
        maxInput.value = currentMax;

        if (window.location.search.indexOf('price_min=') === -1) {
            minInput.value = '';
            maxInput.value = '';
        }
    }

    function initSortDropdown() {
        var sortEl = document.getElementById('hotel-sort');
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
            applyHotelFilters();
        };

        if (typeof jQuery !== 'undefined') {
            jQuery(sortEl).off('change.hotelSort').on('change.hotelSort', handler);
        } else {
            sortEl.removeEventListener('change', handler);
            sortEl.addEventListener('change', handler);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        var applyBtn = document.getElementById('hotel-apply-filters');
        var clearBtn = document.getElementById('hotel-clear-filters');

        if (applyBtn) {
            applyBtn.addEventListener('click', function (e) {
                e.preventDefault();
                applyHotelFilters();
            });
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', function (e) {
                e.preventDefault();
                clearHotelFilters();
            });
        }

        initPriceSlider();
        initSortDropdown();
    });
})();
