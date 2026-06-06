@php
    $filterAction = $filterAction ?? url()->current();
    $filterParams = collect($filterGroups ?? [])->pluck('param')->all();
    $excludeFromPreserve = array_merge($filterParams, ['page']);
    $hasActiveFilters = collect($filterParams)->contains(fn ($param) => request()->has($param));
    $searchQuery = collect(request()->query())->except($filterParams)->all();
    $clearUrl = $searchQuery !== [] ? $filterAction.'?'.http_build_query($searchQuery) : $filterAction;
@endphp

@if(!empty($filterGroups))
<form method="GET" action="{{ $filterAction }}" id="catalog-filters-form">
    <x-catalog-search-preserved-inputs :except="$excludeFromPreserve" />
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
        @if($hasActiveFilters)
            <a href="{{ $clearUrl }}" class="btn btn-link btn-sm w-100 mt-1">Clear filters</a>
        @endif
    </div>
</form>
@endif
