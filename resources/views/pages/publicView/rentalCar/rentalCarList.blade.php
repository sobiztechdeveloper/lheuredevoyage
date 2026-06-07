@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

<x-catalog-list-hero
    :title="$items->total().' '.Str::plural('Car', $items->total()).' Found'"
    page="rentalcar"
>
    <x-slot:breadcrumb>
        <li><a href="{{ route('rentalcar') }}">Cars</a></li>
        <li class="active">Search Results</li>
    </x-slot:breadcrumb>
    <x-slot:search>
        <div class="search-wrapper">
            <!-- car search -->
            <div class="search-box car-search">
                <div class="search-form">
                    <form method="GET" action="{{ route('rentalcar') }}">
                        <x-catalog-search-preserved-inputs :except="['destination', 'q', 'page', 'pickup-date', 'pick-up-time', 'return-date', 'drop-off-time', 'dropoff', 'sort', 'vehicle_types', 'vehicle_features']" />
                        <div class="car-search-wrapper">
                            <div class="row">
                                <div class="col-lg-4">
                                    <x-destination-autocomplete
                                        name="destination"
                                        context="car_pickup"
                                        :value="request('destination', request('q', ''))"
                                        label="Picking Up"
                                        icon="fal fa-location-dot"
                                        placeholder="City, airport or address"
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="search-form-date">
                                            <div class="search-form-journey">
                                                <label>Pick Up date</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="pickup-date"
                                                        class="form-control date-picker journey-date" value="{{ request('pickup-date') }}">
                                                    <i class="fal fa-calendar-days"></i>
                                                </div>
                                                <p class="journey-day-name"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Pick Up Time</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="pick-up-time"
                                                class="form-control time-picker" value="{{ request('pick-up-time', '11:00 PM') }}">
                                            <i class="fal fa-clock"></i>
                                        </div>
                                        <p>Car Arrival Time</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <x-destination-autocomplete
                                        class="mt-lg-4"
                                        name="dropoff"
                                        context="car_dropoff"
                                        :value="request('dropoff')"
                                        label="Drop Off"
                                        icon="fal fa-location-dot"
                                        placeholder="Drop-off location"
                                    />
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mt-lg-4">
                                        <div class="search-form-date">
                                            <div class="search-form-journey">
                                                <label>Drop Off date</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="return-date"
                                                        class="form-control date-picker return-date" value="{{ request('return-date') }}">
                                                    <i class="fal fa-calendar-days"></i>
                                                </div>
                                                <p class="journey-day-name"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group mt-lg-4">
                                        <label>Drop Off Time</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="drop-off-time"
                                                class="form-control time-picker" value="{{ request('drop-off-time', '11:00 PM') }}">
                                            <i class="fal fa-clock"></i>
                                        </div>
                                        <p>Car Drop Off Time</p>
                                    </div>
                                </div>
                            </div>
                            <div class="search-btn">
                                <button type="submit" class="theme-btn"><span
                                        class="far fa-search"></span>Search Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- car search end -->
        </div>
    </x-slot:search>
</x-catalog-list-hero>


<!-- car grid -->
<div class="car-grid catalog-list-results">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-3 mb-4">
                @include('partials.catalog.filters-sidebar', [
                    'filterGroups' => $filterGroups ?? [],
                    'filterAction' => route('rentalcar'),
                ])
            </div>
            <div class="col-lg-8 col-xl-9">
                <div class="booking-sort mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="mb-0">{{ $items->total() }} {{ Str::plural('Car', $items->total()) }} Found</h5>
                    <form method="GET" action="{{ route('rentalcar') }}" class="booking-sort-box" style="min-width:220px;">
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
                            <option value="name" @selected(request('sort') === 'name')>Car Name</option>
                        </select>
                    </form>
                </div>

                @include('components.catalog-list-results', [
                    'items' => $items,
                    'routePrefix' => $routePrefix ?? 'rentalcar',
                    'label' => 'Cars',
                    'showHeader' => false,
                    'searchQuery' => $searchQuery ?? [],
                    'emptyType' => 'rentalcar',
                ])

                <x-catalog-quote-banner type="rentalcar" :search-query="$searchQuery ?? []" />
            </div>
        </div>
    </div>
</div>
<!-- car grid end -->

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection