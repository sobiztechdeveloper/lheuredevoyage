@php
    $filterAction = route('cruise.search');
    $preserveKeys = app(\App\Services\CruiseSearchService::class)->preserveQueryKeys();
    $priceBounds = $priceBounds ?? ['min' => 0, 'max' => 5000];
    $activeMin = request('min_price', $activeMinPrice ?? $priceBounds['min']);
    $activeMax = request('max_price', $activeMaxPrice ?? $priceBounds['max']);
@endphp

<form method="GET" action="{{ $filterAction }}" id="cruise-filters-form">
    @foreach($preserveKeys as $key)
        @if(in_array($key, ['min_price', 'max_price', 'region', 'duration', 'cabin_type', 'featured_only', 'sort', 'categories', 'facilities'], true))
            @continue
        @endif
        @if(is_array(request($key)))
            @foreach(request($key) as $item)
                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
            @endforeach
        @elseif(request()->filled($key))
            <input type="hidden" name="{{ $key }}" value="{{ request($key) }}">
        @endif
    @endforeach

    <div class="booking-sidebar">
        @foreach($filterGroups ?? [] as $group)
            @if($group['options']->isNotEmpty())
            <div class="booking-item">
                <h4 class="booking-title">{{ $group['label'] }}</h4>
                <div class="facility">
                    @foreach($group['options'] as $option)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox"
                            name="{{ $group['param'] }}[]" value="{{ $option->id }}"
                            id="cruise-filter-{{ $group['param'] }}-{{ $option->id }}"
                            @checked(in_array($option->id, array_map('intval', (array) request($group['param'], []))))>
                        <label class="form-check-label" for="cruise-filter-{{ $group['param'] }}-{{ $option->id }}">
                            {{ $option->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach

        @if(!empty($regionOptions))
        <div class="booking-item">
            <h4 class="booking-title">Region</h4>
            <div class="facility">
                @foreach($regionOptions as $value => $label)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="region[]" value="{{ $value }}"
                        id="cruise-region-{{ $value }}"
                        @checked(in_array($value, (array) request('region', []), true))>
                    <label class="form-check-label" for="cruise-region-{{ $value }}">{{ $label }}</label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($durationOptions))
        <div class="booking-item">
            <h4 class="booking-title">Duration (nights)</h4>
            <div class="facility">
                @foreach($durationOptions as $value => $label)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="duration[]" value="{{ $value }}"
                        id="cruise-duration-{{ $value }}"
                        @checked(in_array($value, (array) request('duration', []), true))>
                    <label class="form-check-label" for="cruise-duration-{{ $value }}">{{ $label }}</label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($cabinTypeOptions))
        <div class="booking-item">
            <h4 class="booking-title">Cabin Type</h4>
            <div class="facility">
                @foreach($cabinTypeOptions as $value => $label)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="cabin_type[]" value="{{ $value }}"
                        id="cruise-cabin-{{ $value }}"
                        @checked(in_array($value, (array) request('cabin_type', []), true))>
                    <label class="form-check-label" for="cruise-cabin-{{ $value }}">{{ $label }}</label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="booking-item">
            <h4 class="booking-title">Price (CHF)</h4>
            <div class="row g-2">
                <div class="col-6">
                    <label class="form-label small">Min</label>
                    <input type="number" name="min_price" class="form-control form-control-sm"
                        min="{{ $priceBounds['min'] }}" max="{{ $priceBounds['max'] }}"
                        value="{{ $activeMin }}" step="50">
                </div>
                <div class="col-6">
                    <label class="form-label small">Max</label>
                    <input type="number" name="max_price" class="form-control form-control-sm"
                        min="{{ $priceBounds['min'] }}" max="{{ $priceBounds['max'] }}"
                        value="{{ $activeMax }}" step="50">
                </div>
            </div>
        </div>

        <div class="booking-item">
            <h4 class="booking-title">More</h4>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="featured_only" value="1" id="cruise-featured-only"
                    @checked(request()->boolean('featured_only'))>
                <label class="form-check-label" for="cruise-featured-only">Featured cruises only</label>
            </div>
        </div>

        <button type="submit" class="theme-btn btn-sm w-100 mt-2">Apply Filters</button>
        @if(request()->hasAny(['region', 'duration', 'cabin_type', 'min_price', 'max_price', 'featured_only', 'categories', 'facilities']))
            <a href="{{ route('cruise', request()->only(['destination', 'q', 'journey-date', 'return-date', 'cruise_line', 'adult', 'children', 'infant'])) }}"
                class="btn btn-link btn-sm w-100 mt-1">Clear filters</a>
        @endif
    </div>
</form>
