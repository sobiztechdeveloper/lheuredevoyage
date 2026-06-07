(function ($) {
    'use strict';

    function displayText(item, format) {
        if (format === 'airport' && item.airport_label) {
            return item.airport_label;
        }

        return item.text || item.label || item.name || '';
    }

    function mapApiItems(data, format) {
        var items = Array.isArray(data) ? data : (data.data || []);

        return items.map(function (item) {
            var text = displayText(item, format);

            return {
                label: text,
                value: text,
                meta: item
            };
        });
    }

    function initDestinationAutocomplete($input) {
        if (!$input.length || $input.data('destinationAutocompleteInit')) {
            return;
        }

        if (!$.fn.autocomplete) {
            return;
        }

        var apiUrl = $input.data('api-url') || '/api/destinations/search';
        var types = ($input.data('types') || '').toString();
        var context = ($input.data('context') || '').toString();
        var format = ($input.data('format') || 'default').toString();
        var minChars = 2;
        var delayMs = (context === 'flight_from' || context === 'flight_to') ? 500 : 200;
        var xhr = null;
        var cachedSuggestions = null;
        var $wrap = $input.closest('.destination-autocomplete-wrap');
        var $meta = $wrap.find('.destination-field-meta');
        var hiddenFieldName = context === 'flight_from'
            ? 'from_departure_id'
            : (context === 'flight_to' ? 'to_arrival_id' : null);

        function ensureHiddenField() {
            if (!hiddenFieldName) {
                return $();
            }

            var $hidden = $wrap.find('input[type="hidden"][data-serpapi-id]');

            if (!$hidden.length) {
                $hidden = $('<input>', {
                    type: 'hidden',
                    name: hiddenFieldName,
                    'data-serpapi-id': '1'
                });
                $wrap.append($hidden);
            }

            return $hidden;
        }

        function fetchDestinations(term, callback) {
            if (xhr) {
                xhr.abort();
            }

            var payload = {
                query: term || ''
            };

            if (types) {
                payload.type = types;
            }

            if (context) {
                payload.context = context;
            }

            if (format) {
                payload.format = format;
            }

            xhr = $.getJSON(apiUrl, payload)
                .done(function (data) {
                    callback(mapApiItems(data, format));
                })
                .fail(function () {
                    callback([]);
                });
        }

        function filterItems(items, term) {
            if (!term) {
                return items;
            }

            var matcher = new RegExp($.ui.autocomplete.escapeRegex(term), 'i');

            return items.filter(function (item) {
                return matcher.test(item.label) || matcher.test(item.value);
            });
        }

        function updateMeta(text) {
            if (!$meta.length) {
                return;
            }

            $meta.text(text || '\u00a0');
        }

        $input.autocomplete({
            minLength: 0,
            delay: delayMs,
            appendTo: $input.closest('.destination-autocomplete-wrap'),
            classes: {
                'ui-autocomplete': 'destination-autocomplete-menu'
            },
            position: {
                my: 'left top+6',
                at: 'left bottom',
                collision: 'flipfit'
            },
            source: function (request, response) {
                var term = (request.term || '').trim();

                if (term.length >= minChars) {
                    fetchDestinations(term, response);

                    return;
                }

                if (cachedSuggestions) {
                    response(filterItems(cachedSuggestions, term));

                    return;
                }

                fetchDestinations('', function (items) {
                    cachedSuggestions = items;
                    response(filterItems(items, term));
                });
            },
            select: function (event, ui) {
                $input.val(ui.item.value);
                updateMeta(ui.item.meta && ui.item.meta.country ? ui.item.meta.country : '');

                if (hiddenFieldName) {
                    var serpapiId = ui.item.meta && (ui.item.meta.serpapi_id || ui.item.meta.code)
                        ? (ui.item.meta.serpapi_id || ui.item.meta.code)
                        : '';
                    ensureHiddenField().val(serpapiId);
                }

                $input.trigger('change');

                return false;
            },
            open: function () {
                $input.autocomplete('widget').outerWidth($input.outerWidth());
            }
        });

        function openSuggestions() {
            if (!$input.autocomplete('widget').is(':visible')) {
                $input.autocomplete('search', $input.val());
            }
        }

        $input.on('focus click', function () {
            openSuggestions();
        });

        $input.on('input', function () {
            var val = ($input.val() || '').trim();

            if (val.length < minChars) {
                updateMeta('\u00a0');
                if (hiddenFieldName) {
                    ensureHiddenField().val('');
                }
            }
        });

        if (($input.val() || '').trim()) {
            updateMeta('\u00a0');
        } else {
            updateMeta('\u00a0');
        }

        $input.data('destinationAutocompleteInit', true);
    }

    function boot() {
        $('[data-destination-autocomplete-input]').each(function () {
            initDestinationAutocomplete($(this));
        });
    }

    $(document).ready(boot);
    document.addEventListener('turbo:load', boot);
})(jQuery);
