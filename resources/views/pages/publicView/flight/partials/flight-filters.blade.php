@php
    $facets = $facets ?? [];
    $activeFilters = $activeFilters ?? [];
    $filterAction = $filterAction ?? route('flight');
    $panel = $panelSearch ?? [];
@endphp
<form id="flight-results-filters" method="GET" action="{{ $filterAction }}" data-flight-search-id="{{ $search->id ?? '' }}">
    <input type="hidden" name="flight_search" value="{{ $search->id }}">
    <input type="hidden" name="from-destination" value="{{ $panel['from_destination'] ?? '' }}">
    <input type="hidden" name="to-destination" value="{{ $panel['to_destination'] ?? '' }}">
    <input type="hidden" name="journey-date" value="{{ $panel['journey_date'] ?? '' }}">
    <input type="hidden" name="return-date" value="{{ $panel['return_date'] ?? '' }}">
    <input type="hidden" name="flight-type" value="{{ $panel['flight_type'] ?? 'one-way' }}">
    <input type="hidden" name="adult" value="{{ $panel['adult'] ?? 2 }}">
    <input type="hidden" name="children" value="{{ $panel['children'] ?? 0 }}">
    <input type="hidden" name="infant" value="{{ $panel['infant'] ?? 0 }}">
    @if(!empty($panel['from_departure_id']))
        <input type="hidden" name="from_departure_id" value="{{ $panel['from_departure_id'] }}">
    @endif
    @if(!empty($panel['to_arrival_id']))
        <input type="hidden" name="to_arrival_id" value="{{ $panel['to_arrival_id'] }}">
    @endif
    <input type="hidden" name="sort" id="filter-sort" value="{{ $sort ?? 'price_asc' }}">
    <input type="hidden" name="price_min" id="filter-price-min" value="{{ request()->has('price_min') ? (int) $priceMin : '' }}">
    <input type="hidden" name="price_max" id="filter-price-max" value="{{ request()->has('price_max') ? (int) $priceMax : '' }}">

    <div class="booking-item">
        <h4 class="booking-title">Flight Class</h4>
        <div class="flight-class">
            @foreach($facets['classes'] ?? [] as $facet)
            <div class="form-check">
                <input class="form-check-input flight-list-filter flight-list-cabin" name="cabin_class" type="radio" value="{{ $facet['value'] }}"
                    id="flight-class-{{ $facet['value'] }}" @checked(($panel['cabin_class'] ?? 'economy') === $facet['value'])>
                <label class="form-check-label" for="flight-class-{{ $facet['value'] }}">
                    {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>

    <div class="booking-item">
        <h4 class="booking-title">Flight Price ({{ display_currency() }})</h4>
        <div class="flight-price">
            <div class="price-range-slider">
                <div class="price-range-info">
                    <label for="priceRange1">Price:</label>
                    <input type="text" class="priceRange" id="priceRange1" readonly
                        data-currency-prefix="{{ display_currency() === 'CHF' ? 'CHF ' : '$' }}"
                        data-min="{{ (int) ($facets['price_min'] ?? 0) }}"
                        data-max="{{ (int) ($facets['price_max'] ?? 1000) }}"
                        data-current-min="{{ (int) ($priceMin ?? $facets['price_min'] ?? 0) }}"
                        data-current-max="{{ (int) ($priceMax ?? $facets['price_max'] ?? 1000) }}">
                </div>
                <div id="price-range1" class="price-range slider"></div>
            </div>
        </div>
    </div>

    @if(!empty($facets['times']))
    <div class="booking-item">
        <h4 class="booking-title">Flight Time</h4>
        <div class="flight-time">
            @foreach($facets['times'] as $facet)
            @php
                $icon = match($facet['value']) {
                    '1' => 'fa-sunrise',
                    '2' => 'fa-sun-bright',
                    '3' => 'fa-sunset',
                    default => 'fa-moon-stars',
                };
            @endphp
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_time[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-time-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_time'] ?? [], true))>
                <label class="form-check-label" for="flight-time-{{ $facet['value'] }}">
                    <i class="far {{ $icon }}"></i> {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($facets['arrival_times']))
    <div class="booking-item">
        <h4 class="booking-title">Arrival Time</h4>
        <div class="flight-arrival-time">
            @foreach($facets['arrival_times'] as $facet)
            @php
                $icon = match($facet['value']) {
                    '1' => 'fa-sunrise',
                    '2' => 'fa-sun-bright',
                    '3' => 'fa-sunset',
                    default => 'fa-moon-stars',
                };
            @endphp
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_arrival_time[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-arrival-time-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_arrival_time'] ?? [], true))>
                <label class="form-check-label" for="flight-arrival-time-{{ $facet['value'] }}">
                    <i class="far {{ $icon }}"></i> {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($facets['durations']))
    <div class="booking-item">
        <h4 class="booking-title">Duration</h4>
        <div class="flight-duration">
            @foreach($facets['durations'] as $facet)
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_duration[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-duration-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_duration'] ?? [], true))>
                <label class="form-check-label" for="flight-duration-{{ $facet['value'] }}">
                    {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($facets['stops']))
    <div class="booking-item">
        <h4 class="booking-title">Flight Stops</h4>
        <div class="flight-stop">
            @foreach($facets['stops'] as $facet)
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_stop[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-stop-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_stop'] ?? [], true))>
                <label class="form-check-label" for="flight-stop-{{ $facet['value'] }}">
                    {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($facets['overnight']))
    <div class="booking-item">
        <h4 class="booking-title">Overnight</h4>
        <div class="flight-overnight">
            @foreach($facets['overnight'] as $facet)
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_overnight[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-overnight-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_overnight'] ?? [], true))>
                <label class="form-check-label" for="flight-overnight-{{ $facet['value'] }}">
                    {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($facets['layovers']))
    <div class="booking-item">
        <h4 class="booking-title">Layover Airport</h4>
        <div class="flight-layover">
            @foreach($facets['layovers'] as $facet)
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_layover[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-layover-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_layover'] ?? [], true))>
                <label class="form-check-label" for="flight-layover-{{ $facet['value'] }}">
                    {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($facets['airlines']))
    <div class="booking-item">
        <h4 class="booking-title">Airlines</h4>
        <div class="flight-airline">
            @foreach($facets['airlines'] as $facet)
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_airline[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-airline-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_airline'] ?? [], true))>
                <label class="form-check-label d-flex align-items-center gap-2" for="flight-airline-{{ $facet['value'] }}">
                    @if(!empty($facet['logo_url']))
                        <img src="{{ $facet['logo_url'] }}" alt="" width="20" height="20" class="object-fit-contain">
                    @endif
                    <span>{{ $facet['label'] }} <span>({{ $facet['count'] }})</span></span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($facets['weights']))
    <div class="booking-item">
        <h4 class="booking-title">Weights</h4>
        <div class="flight-weight">
            @foreach($facets['weights'] as $facet)
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_weight[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-weight-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_weight'] ?? [], true))>
                <label class="form-check-label" for="flight-weight-{{ $facet['value'] }}">
                    {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if(!empty($facets['refundable']))
    <div class="booking-item">
        <h4 class="booking-title">Refundable</h4>
        <div class="flight-refundable">
            @foreach($facets['refundable'] as $facet)
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_refundable[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-refundable-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_refundable'] ?? [], true))>
                <label class="form-check-label" for="flight-refundable-{{ $facet['value'] }}">
                    {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="booking-item flight-filter-actions">
        <button type="button" id="flight-apply-filters" class="theme-btn">Apply Filters</button>
        <button type="button" id="flight-clear-filters" class="btn-clear-filters">Clear All Filters</button>
    </div>
</form>
