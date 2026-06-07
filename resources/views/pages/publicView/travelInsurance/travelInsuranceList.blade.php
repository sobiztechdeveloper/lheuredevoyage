@extends('layouts.app')

@section('body-class', 'home-3')

@section('content')

<x-catalog-list-hero
    :title="$items->total().' '.Str::plural('Plan', $items->total()).' Found'"
    page="travelinsurance"
>
    <x-slot:breadcrumb>
        <li><a href="{{ route('travelinsurance') }}">Travel Insurance</a></li>
        <li class="active">Compare Plans</li>
    </x-slot:breadcrumb>
    <x-slot:search>
        <div class="search-wrapper">
            <div class="search-box tour-search">
                <div class="search-form">
                    <form method="GET" action="{{ route('travelinsurance') }}">
                        <x-catalog-search-preserved-inputs :except="['destination', 'q', 'page', 'journey-date', 'return-date', 'travelers', 'sort', 'plan_type', 'schengen', 'worldwide', 'featured_only', 'insurance_types', 'coverage_types']" />
                        <div class="tour-search-wrapper">
                            <div class="row align-items-end">
                                <div class="col-lg-4">
                                    <x-destination-autocomplete
                                        name="destination"
                                        context="insurance"
                                        :value="$activeDestination ?? ''"
                                        label="Destination"
                                        icon="fal fa-earth-americas"
                                        placeholder="Country or region"
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="search-form-date">
                                            <div class="search-form-journey">
                                                <label>Departure</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="journey-date"
                                                        class="form-control date-picker journey-date"
                                                        value="{{ request('journey-date') }}">
                                                    <i class="fal fa-calendar-days"></i>
                                                </div>
                                                <p class="journey-day-name"></p>
                                            </div>
                                            <div class="search-form-return">
                                                <label>Return</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="return-date"
                                                        class="form-control date-picker return-date"
                                                        value="{{ request('return-date') }}">
                                                </div>
                                                <p class="return-day-name"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <x-insurance-traveler-picker :value="request('travelers', 1)" />
                                </div>
                            </div>
                            <div class="search-btn">
                                <button type="submit" class="theme-btn">
                                    <span class="far fa-search"></span> Search Plans
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-slot:search>
</x-catalog-list-hero>

<!-- insurance plans grid -->
<div class="tour-grid catalog-list-results">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-3 mb-4">
                @include('partials.catalog.insurance-filters-sidebar', [
                    'filterGroups' => $filterGroups ?? [],
                    'planTypeOptions' => $planTypeOptions ?? [],
                ])
            </div>
            <div class="col-lg-8 col-xl-9">
                <div class="booking-sort mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="mb-0">{{ $items->total() }} {{ Str::plural('Insurance Plan', $items->total()) }} Found</h5>
                    <form method="GET" action="{{ route('travelinsurance') }}" class="booking-sort-box" style="min-width:220px;">
                        @foreach(request()->except(['sort', 'page']) as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $item)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        <select name="sort" class="select" onchange="this.form.submit()">
                            <option value="default" @selected(request('sort', 'default') === 'default')>Sort: Recommended</option>
                            <option value="price_asc" @selected(request('sort') === 'price_asc')>Price: Low to High</option>
                            <option value="price_desc" @selected(request('sort') === 'price_desc')>Price: High to Low</option>
                            <option value="name" @selected(request('sort') === 'name')>Plan Name</option>
                        </select>
                    </form>
                </div>

                <x-insurance-list-results :items="$items" :search-query="$searchQuery ?? []" :show-header="false" />

                <x-catalog-quote-banner type="travelinsurance" :search-query="$searchQuery ?? []" />
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.insurance-plan-item .hotel-bottom { align-items: flex-end; }
.insurance-plan-item .hotel-img img { object-fit: cover; min-height: 220px; }
</style>
@endpush
