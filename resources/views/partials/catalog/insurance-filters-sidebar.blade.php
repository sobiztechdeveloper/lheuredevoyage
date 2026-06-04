<form method="GET" action="{{ route('travelinsurance.search') }}" id="insurance-filters-form">
    @foreach(['destination', 'q', 'journey-date', 'return-date', 'travelers', 'sort'] as $preserve)
        @if(request()->filled($preserve))
            <input type="hidden" name="{{ $preserve }}" value="{{ request($preserve) }}">
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
                            id="filter-{{ $group['param'] }}-{{ $option->id }}"
                            @checked(in_array($option->id, array_map('intval', (array) request($group['param'], []))))>
                        <label class="form-check-label" for="filter-{{ $group['param'] }}-{{ $option->id }}">
                            {{ $option->name }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach

        @if(!empty($planTypeOptions))
        <div class="booking-item">
            <h4 class="booking-title">Plan Type</h4>
            <div class="facility">
                @foreach($planTypeOptions as $value => $label)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="plan_type[]" value="{{ $value }}"
                        id="ins-plan-type-{{ $value }}"
                        @checked(in_array($value, (array) request('plan_type', []), true))>
                    <label class="form-check-label" for="ins-plan-type-{{ $value }}">{{ $label }}</label>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <div class="booking-item">
            <h4 class="booking-title">Coverage</h4>
            <div class="facility">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="schengen" value="1" id="ins-filter-schengen"
                        @checked(request()->boolean('schengen'))>
                    <label class="form-check-label" for="ins-filter-schengen">Schengen eligible</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="worldwide" value="1" id="ins-filter-worldwide"
                        @checked(request()->boolean('worldwide'))>
                    <label class="form-check-label" for="ins-filter-worldwide">Worldwide coverage</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="featured_only" value="1" id="ins-filter-featured"
                        @checked(request()->boolean('featured_only'))>
                    <label class="form-check-label" for="ins-filter-featured">Featured plans only</label>
                </div>
            </div>
        </div>

        <button type="submit" class="theme-btn btn-sm w-100 mt-2">Apply Filters</button>
        @if(request()->hasAny(['plan_type', 'schengen', 'worldwide', 'featured_only', 'insurance_types', 'coverage_types']))
            <a href="{{ route('travelinsurance', request()->only(['destination', 'q', 'journey-date', 'return-date', 'travelers'])) }}" class="btn btn-link btn-sm w-100 mt-1">Clear filters</a>
        @endif
    </div>
</form>
