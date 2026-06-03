@php
    $facets = $facets ?? [];
    $activeFilters = $activeFilters ?? [];
    $filterAction = $filterAction ?? route('flight');
@endphp
<form id="flight-results-filters" method="GET" action="{{ $filterAction }}" data-flight-search-id="{{ $search->id ?? '' }}">
    <input type="hidden" name="flight_search" value="{{ $search->id }}">
    <input type="hidden" name="sort" id="filter-sort" value="{{ $sort ?? 'price_asc' }}">
    <input type="hidden" name="price_min" id="filter-price-min" value="{{ request()->has('price_min') ? (int) $priceMin : '' }}">
    <input type="hidden" name="price_max" id="filter-price-max" value="{{ request()->has('price_max') ? (int) $priceMax : '' }}">

    @if(!empty($facets['classes']))
    <div class="booking-item">
        <h4 class="booking-title">Flight Class</h4>
        <div class="flight-class">
            @foreach($facets['classes'] as $facet)
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_class[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-class-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_class'] ?? [], true))>
                <label class="form-check-label" for="flight-class-{{ $facet['value'] }}">
                    {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="booking-item">
        <h4 class="booking-title">Flight Price</h4>
        <div class="flight-price">
            <div class="price-range-slider">
                <div class="price-range-info">
                    <label for="priceRange1">Price:</label>
                    <input type="text" class="priceRange" id="priceRange1" readonly
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

    @if(!empty($facets['airlines']))
    <div class="booking-item">
        <h4 class="booking-title">Airlines</h4>
        <div class="flight-airline">
            @foreach($facets['airlines'] as $facet)
            <div class="form-check">
                <input class="form-check-input flight-list-filter" name="flight_airline[]" type="checkbox" value="{{ $facet['value'] }}"
                    id="flight-airline-{{ $facet['value'] }}" @checked(in_array($facet['value'], $activeFilters['flight_airline'] ?? [], true))>
                <label class="form-check-label" for="flight-airline-{{ $facet['value'] }}">
                    {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
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
