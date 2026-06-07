@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

@php
    $flightListTitle = isset($resultsCount)
        ? number_format($resultsCount) . (isset($totalResultsCount) && $totalResultsCount !== $resultsCount ? ' of ' . number_format($totalResultsCount) : '') . ' Results Found'
        : '0 Results Found';
@endphp

<x-catalog-list-hero :title="$flightListTitle" page="flight" search-area-class="flight-search">
    <x-slot:breadcrumb>
        <li class="active">Flight Search</li>
    </x-slot:breadcrumb>
    <x-slot:search>
        <div class="search-wrapper">
            <div class="search-box">
                <div class="flight-search ft-group">
                    <div class="search-form">
                    <form method="POST" action="{{ isset($search) ? route('flight.search.update', $search) : route('flight.search.submit') }}">
                        @csrf
                        @isset($search)
                            @if(!empty($activeFilters['flight_class']))
                                @foreach($activeFilters['flight_class'] as $filterValue)
                                    <input type="hidden" name="flight_class[]" value="{{ $filterValue }}">
                                @endforeach
                            @endif
                            @if(!empty($activeFilters['flight_time']))
                                @foreach($activeFilters['flight_time'] as $filterValue)
                                    <input type="hidden" name="flight_time[]" value="{{ $filterValue }}">
                                @endforeach
                            @endif
                            @if(!empty($activeFilters['flight_stop']))
                                @foreach($activeFilters['flight_stop'] as $filterValue)
                                    <input type="hidden" name="flight_stop[]" value="{{ $filterValue }}">
                                @endforeach
                            @endif
                            @if(!empty($activeFilters['flight_airline']))
                                @foreach($activeFilters['flight_airline'] as $filterValue)
                                    <input type="hidden" name="flight_airline[]" value="{{ $filterValue }}">
                                @endforeach
                            @endif
                            @if(!empty($activeFilters['flight_weight']))
                                @foreach($activeFilters['flight_weight'] as $filterValue)
                                    <input type="hidden" name="flight_weight[]" value="{{ $filterValue }}">
                                @endforeach
                            @endif
                            @if(!empty($activeFilters['flight_refundable']))
                                @foreach($activeFilters['flight_refundable'] as $filterValue)
                                    <input type="hidden" name="flight_refundable[]" value="{{ $filterValue }}">
                                @endforeach
                            @endif
                            @if(!empty($activeFilters['price_min']))
                                <input type="hidden" name="price_min" value="{{ $activeFilters['price_min'] }}">
                            @endif
                            @if(!empty($activeFilters['price_max']))
                                <input type="hidden" name="price_max" value="{{ $activeFilters['price_max'] }}">
                            @endif
                            @if(!empty($activeFilters['sort']))
                                <input type="hidden" name="sort" value="{{ $activeFilters['sort'] }}">
                            @endif
                        @endisset
                        <!-- flight type -->
                        <div class="flight-type">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" @checked(!isset($search) || $search->trip_type === 'one_way') value="one-way"
                                    name="flight-type" id="flight-type1">
                                <label class="form-check-label" for="flight-type1">
                                    One Way
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" @checked(isset($search) && $search->trip_type === 'round_trip') value="round-way"
                                    name="flight-type" id="flight-type2">
                                <label class="form-check-label" for="flight-type2">
                                    Round Way
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="multi-city"
                                    name="flight-type" id="flight-type3">
                                <label class="form-check-label" for="flight-type3">
                                    Multi City
                                </label>
                            </div>
                        </div>
                        <!-- flight type end -->

                        <!-- flight search wrapper -->
                        <div class="flight-search-wrapper">
                            <div class="flight-search-content">
                                <!-- flight search content -->
                                <div class="flight-search-item">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <x-destination-autocomplete
                                                name="from-destination"
                                                context="flight_from"
                                                format="airport"
                                                :value="$search->from_destination ?? ''"
                                                label="From"
                                                icon="fal fa-plane-departure"
                                                input-class="form-control swap-from"
                                                placeholder="City or airport"
                                            />
                                        </div>
                                        <div class="col-lg-3">
                                            <x-destination-autocomplete
                                                name="to-destination"
                                                context="flight_to"
                                                format="airport"
                                                :value="$search->to_destination ?? ''"
                                                label="To"
                                                icon="fal fa-plane-arrival"
                                                input-class="form-control swap-to"
                                                placeholder="City or airport"
                                                :swap="true"
                                            />
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="search-form-date">
                                                    <div class="search-form-journey">
                                                        <label>Journey Date</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="journey-date"
                                                                class="form-control date-picker journey-date" value="{{ isset($search) ? $search->journey_date->format(config('date.display')) : '' }}">
                                                            <i class="fal fa-calendar-days"></i>
                                                        </div>
                                                        <p class="journey-day-name"></p>
                                                    </div>
                                                    <div class="search-form-return">
                                                        <label>Return Date</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="return-date"
                                                                class="form-control date-picker return-date" value="{{ isset($search) && $search->return_date ? $search->return_date->format(config('date.display')) : '' }}">
                                                        </div>
                                                        <p class="return-day-name"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group dropdown static-dropdown passenger-box">
                                                <div class="passenger-class" role="menu" data-bs-toggle="dropdown"
                                                    data-bs-display="static" aria-expanded="false">
                                                    <label>Passenger, Class</label>
                                                    <div class="form-group-icon">
                                                        <div class="passenger-total"><span
                                                                class="passenger-total-amount">{{ $search->adult ?? 2 }}</span>
                                                            Passenger
                                                        </div>
                                                        <i class="fal fa-user-tie-hair"></i>
                                                    </div>
                                                    <p class="passenger-class-name">{{ isset($search) ? (\App\Services\FlightSearchResultsService::CABIN_LABELS[$search->cabin_class] ?? ucfirst($search->cabin_class)) : 'Economy' }}</p>
                                                </div>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    <div class="dropdown-item">
                                                        <div class="passenger-item">
                                                            <div class="passenger-info">
                                                                <h6>Adults</h6>
                                                                <p>12+ Years</p>
                                                            </div>
                                                            <div class="passenger-qty">
                                                                <button type="button" class="minus-btn"><i
                                                                        class="far fa-minus"></i></button>
                                                                <input type="text" name="adult"
                                                                    class="qty-amount passenger-adult" value="{{ $search->adult ?? 2 }}"
                                                                    readonly>
                                                                <button type="button" class="plus-btn"><i
                                                                        class="far fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-item">
                                                        <div class="passenger-item">
                                                            <div class="passenger-info">
                                                                <h6>Children</h6>
                                                                <p>2-12 Years</p>
                                                            </div>
                                                            <div class="passenger-qty">
                                                                <button type="button" class="minus-btn"><i
                                                                        class="far fa-minus"></i></button>
                                                                <input type="text" name="children"
                                                                    class="qty-amount passenger-children"
                                                                    value="{{ $search->children ?? 0 }}" readonly>
                                                                <button type="button" class="plus-btn"><i
                                                                        class="far fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-item">
                                                        <div class="passenger-item">
                                                            <div class="passenger-info">
                                                                <h6>Infant</h6>
                                                                <p>Below 2 Years</p>
                                                            </div>
                                                            <div class="passenger-qty">
                                                                <button type="button" class="minus-btn"><i
                                                                        class="far fa-minus"></i></button>
                                                                <input type="text" name="infant"
                                                                    class="qty-amount passenger-infant"
                                                                    value="{{ $search->infant ?? 0 }}" readonly>
                                                                <button type="button" class="plus-btn"><i
                                                                        class="far fa-plus"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dropdown-item">
                                                        <h6 class="mb-3 mt-2">Cabin Class</h6>
                                                        <div class="passenger-class-info">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="Economy" name="cabin-class"
                                                                    id="cabin-class1" @checked(!isset($search) || $search->cabin_class === 'economy')>
                                                                <label class="form-check-label"
                                                                    for="cabin-class1">
                                                                    Economy
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input"
                                                                    type="radio" value="Business"
                                                                    name="cabin-class" id="cabin-class2" @checked(isset($search) && $search->cabin_class === 'business')>
                                                                <label class="form-check-label"
                                                                    for="cabin-class2">
                                                                    Business
                                                                </label>
                                                            </div>
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    value="First Class" name="cabin-class"
                                                                    id="cabin-class3" @checked(isset($search) && $search->cabin_class === 'first')>
                                                                <label class="form-check-label"
                                                                    for="cabin-class3">
                                                                    First Class
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- flight search content end -->

                                <!-- flight-multicity-item -->
                                <div class="flight-search-item flight-multicity-item have-to-clone">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <x-destination-autocomplete
                                                name="from-destination"
                                                context="flight_from"
                                                format="airport"
                                                :value="$search->from_destination ?? ''"
                                                label="From"
                                                icon="fal fa-plane-departure"
                                                input-class="form-control swap-from"
                                                placeholder="City or airport"
                                                :disabled="true"
                                            />
                                        </div>
                                        <div class="col-lg-3">
                                            <x-destination-autocomplete
                                                name="to-destination"
                                                context="flight_to"
                                                format="airport"
                                                :value="$search->to_destination ?? ''"
                                                label="To"
                                                icon="fal fa-plane-arrival"
                                                input-class="form-control swap-to"
                                                placeholder="City or airport"
                                                :swap="true"
                                                :disabled="true"
                                            />
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="search-form-date">
                                                    <div class="search-form-journey">
                                                        <label>Journey Date</label>
                                                        <div class="form-group-icon">
                                                            <input type="text" name="journey-date"
                                                                class="form-control date-picker journey-date" value="{{ isset($search) ? $search->journey_date->format(config('date.display')) : '' }}" disabled>
                                                            <i class="fal fa-calendar-days"></i>
                                                        </div>
                                                        <p class="journey-day-name"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <div class="multicity-btn">
                                                    <i class="fal fa-plus-circle"></i> Add
                                                    Another Flight
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- flight multicity end -->
                            </div>
                            <div class="search-btn">
                                <button type="submit" class="theme-btn"><span
                                        class="far fa-search"></span>Update Search</button>
                            </div>
                        </div>
                        <!-- flight search wrapper end -->
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:search>
</x-catalog-list-hero>


<!-- flight booking -->
@php
    $facets = $facets ?? [];
    $activeFilters = $activeFilters ?? [
        'flight_class' => [],
        'flight_time' => [],
        'flight_stop' => [],
        'flight_airline' => [],
        'flight_weight' => [],
        'flight_refundable' => [],
        'price_min' => null,
        'price_max' => null,
        'sort' => 'price_asc',
    ];
@endphp
<div class="flight-booking flight-list catalog-list-results">
    <div class="container">
        <div class="row">
            <!-- booking sidebar -->
            <div class="col-lg-4 col-xl-3 mb-4">
                <div class="booking-sidebar">
                    @if(isset($search))
                        @include('pages.publicView.flight.partials.flight-filters')
                    @endif
                </div>
            </div>

            <!-- booking list -->
            <div class="col-lg-8 col-xl-9">
                <div class="col-md-12">
                    <div class="booking-sort">
                        <h5>
                            @if(isset($resultsCount))
                                {{ number_format($resultsCount) }}@if(isset($totalResultsCount) && $totalResultsCount !== $resultsCount) of {{ number_format($totalResultsCount) }}@endif Results Found
                            @else
                                0 Results Found
                            @endif
                        </h5>
                        <div class="col-md-3 booking-sort-box">
                            <select class="select" id="flight-sort" name="sort">
                                <option value="price_asc" @selected(($sort ?? 'price_asc') === 'price_asc')>Price Low to High</option>
                                <option value="price_desc" @selected(($sort ?? 'price_asc') === 'price_desc')>Price High to Low</option>
                                <option value="departure_asc" @selected(($sort ?? 'price_asc') === 'departure_asc')>Departure Time</option>
                                <option value="arrival_asc" @selected(($sort ?? 'price_asc') === 'arrival_asc')>Arrival Time</option>
                                <option value="duration_asc" @selected(($sort ?? 'price_asc') === 'duration_asc')>Duration</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @forelse(($results ?? []) as $result)
                        @include('pages.publicView.flight.partials.flight-result-item', ['result' => $result])
                    @empty
                        <x-catalog-empty-state
                            type="flight"
                            :has-search="isset($search)"
                            :search-query="$searchQuery ?? []"
                        />
                    @endforelse
                    </div>

                    <x-catalog-quote-banner type="flight" :search-query="$searchQuery ?? []" />

                    @if(isset($resultsCount) && $resultsCount > 0)
                    <div class="pagination-area">
                        <div class="pagination-showing">
                            <p>
                                Showing {{ $resultsCount > 0 ? 1 : 0 }} - {{ $resultsCount }} of {{ $totalResultsCount ?? $resultsCount }} Flights
                            </p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<!-- flight booking end -->

@endsection

@section('modal')

@endsection

@push('styles')
@if(isset($search))
<link rel="stylesheet" href="{{ asset('assets/css/flight-list-filters.css') }}?v={{ filemtime(public_path('assets/css/flight-list-filters.css')) }}">
@endif
@endpush

@push('scripts')
@if(isset($search))
<script src="{{ asset('assets/js/flight-list-filters.js') }}?v={{ filemtime(public_path('assets/js/flight-list-filters.js')) }}"></script>
@endif
@endpush