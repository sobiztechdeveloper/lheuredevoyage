@if(!empty($filterGroups))
<form method="GET" action="{{ url()->current() }}" id="catalog-filters-form">
    @foreach(['destination', 'q'] as $preserve)
        @if(request()->filled($preserve))
            <input type="hidden" name="{{ $preserve }}" value="{{ request($preserve) }}">
        @endif
    @endforeach
    <div class="booking-sidebar">
        @foreach($filterGroups as $group)
            @if($group['options']->isNotEmpty())
            <div class="booking-item">
                <h4 class="booking-title">{{ $group['label'] }}</h4>
                <div class="facility">
                    @foreach($group['options'] as $option)
                    <div class="form-check">
                        <input class="form-check-input catalog-filter-check" type="checkbox"
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
        <button type="submit" class="theme-btn btn-sm w-100 mt-2">Apply Filters</button>
        @if(collect($filterGroups)->pluck('param')->contains(fn ($p) => request()->has($p)))
            <a href="{{ request()->url() }}" class="btn btn-link btn-sm w-100 mt-1">Clear filters</a>
        @endif
    </div>
</form>
@endif
