@php
    $facets = $facets ?? [];
    $activeFilters = $activeFilters ?? [];
    $filterAction = $filterAction ?? route('hotel.search');
@endphp
<form id="hotel-results-filters" method="GET" action="{{ $filterAction }}">
    <input type="hidden" name="hotel_search" value="{{ $search->id }}">
    <input type="hidden" name="sort" id="filter-sort" value="{{ $sort ?? 'default' }}">
    <input type="hidden" name="price_min" id="filter-price-min" value="{{ request()->has('price_min') ? (int) ($priceMin ?? 0) : '' }}">
    <input type="hidden" name="price_max" id="filter-price-max" value="{{ request()->has('price_max') ? (int) ($priceMax ?? 0) : '' }}">

    <div class="booking-sidebar">
        @foreach($filterGroups ?? [] as $group)
            @if($group['options']->isNotEmpty())
            <div class="booking-item">
                <h4 class="booking-title">{{ $group['label'] }}</h4>
                <div class="facility">
                    @foreach($group['options'] as $option)
                    <div class="form-check">
                        <input class="form-check-input hotel-list-filter" type="checkbox"
                            name="{{ $group['param'] }}[]" value="{{ $option->id }}"
                            id="filter-{{ $group['param'] }}-{{ $option->id }}"
                            @checked(in_array($option->id, $activeFilters[$group['param']] ?? [], true))>
                        <label class="form-check-label" for="filter-{{ $group['param'] }}-{{ $option->id }}">
                            {{ $option->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach

        @if(($facets['price_max'] ?? 0) > ($facets['price_min'] ?? 0))
        <div class="booking-item">
            <h4 class="booking-title">Hotel Price</h4>
            <div class="hotel-price">
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
        @endif

        @if(!empty($facets['stars']))
        <div class="booking-item">
            <h4 class="booking-title">Hotel Star</h4>
            <div class="hotel-star">
                @foreach($facets['stars'] as $facet)
                <div class="form-check">
                    <input class="form-check-input hotel-list-filter" name="stars[]" type="checkbox"
                        value="{{ $facet['value'] }}" id="hotel-star-{{ $facet['value'] }}"
                        @checked(in_array($facet['value'], $activeFilters['stars'] ?? [], true))>
                    <label class="form-check-label" for="hotel-star-{{ $facet['value'] }}">
                        {{ $facet['label'] }} <span>({{ $facet['count'] }})</span>
                    </label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <button type="button" class="theme-btn btn-sm w-100 mt-2" id="hotel-apply-filters">Apply Filters</button>
        <button type="button" class="btn btn-link btn-sm w-100 mt-1" id="hotel-clear-filters">Clear filters</button>
    </div>
</form>
