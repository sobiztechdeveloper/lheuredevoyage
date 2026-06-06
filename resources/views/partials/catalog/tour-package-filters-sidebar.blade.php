@php
    $filterAction = route('tourpackage');
    $preserveKeys = app(\App\Services\TourPackageSearchService::class)->preserveQueryKeys();
    $priceBounds = $priceBounds ?? ['min' => 0, 'max' => 5000];
    $activeMin = request('min_price', $activeMinPrice ?? $priceBounds['min']);
    $activeMax = request('max_price', $activeMaxPrice ?? $priceBounds['max']);
@endphp

<form method="GET" action="{{ $filterAction }}" id="tour-package-filters-form">
    @foreach($preserveKeys as $key)
        @if(in_array($key, ['duration', 'min_price', 'max_price', 'holiday_type', 'sort'], true))
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
        @if(!empty($countryOptions))
        <div class="booking-item">
            <h4 class="booking-title">Country</h4>
            <div class="facility">
                @foreach(collect($countryOptions)->except('') as $value => $label)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="country[]" value="{{ $value }}"
                        id="tour-country-{{ \Illuminate\Support\Str::slug($value) }}"
                        @checked(in_array($value, (array) request('country', request('destination') ? [request('destination')] : []), true))>
                    <label class="form-check-label" for="tour-country-{{ \Illuminate\Support\Str::slug($value) }}">{{ $label }}</label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($holidayTypeOptions))
        <div class="booking-item">
            <h4 class="booking-title">Holiday Type</h4>
            <div class="facility">
                @foreach(collect($holidayTypeOptions)->except('') as $value => $label)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="holiday_type[]" value="{{ $value }}"
                        id="tour-holiday-{{ $value }}"
                        @checked(in_array($value, (array) request('holiday_type', request('holiday_type') ? [request('holiday_type')] : []), true))>
                    <label class="form-check-label" for="tour-holiday-{{ $value }}">{{ $label }}</label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if(!empty($durationOptions))
        <div class="booking-item">
            <h4 class="booking-title">Duration</h4>
            <div class="facility">
                @foreach($durationOptions as $value => $label)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="duration[]" value="{{ $value }}"
                        id="tour-duration-{{ $value }}"
                        @checked(in_array($value, (array) request('duration', []), true))>
                    <label class="form-check-label" for="tour-duration-{{ $value }}">{{ $label }}</label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="booking-item">
            <h4 class="booking-title">Price Range</h4>
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

        <button type="submit" class="theme-btn btn-sm w-100 mt-2">Apply Filters</button>
        @if(request()->hasAny(['duration', 'min_price', 'max_price', 'holiday_type']) && (is_array(request('holiday_type')) ? request('holiday_type') !== [] : request()->filled('holiday_type')))
            <a href="{{ route('tourpackage', request()->only(['destination', 'q', 'travel_month', 'adult', 'children', 'infant'])) }}"
                class="btn btn-link btn-sm w-100 mt-1">Clear filters</a>
        @elseif(request()->hasAny(['duration', 'min_price', 'max_price']))
            <a href="{{ route('tourpackage', request()->only(['destination', 'q', 'travel_month', 'adult', 'children', 'infant', 'holiday_type'])) }}"
                class="btn btn-link btn-sm w-100 mt-1">Clear filters</a>
        @endif
    </div>
</form>
