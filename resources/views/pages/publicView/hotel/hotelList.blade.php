@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/05.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">
            @if(isset($resultsCount))
                {{ number_format($resultsCount) }}@if(isset($totalResultsCount) && $totalResultsCount !== $resultsCount) of {{ number_format($totalResultsCount) }}@endif Hotels Found
            @else
                Hotels
            @endif
        </h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="active">Hotel Search</li>
        </ul>
    </div>
</div>
<!-- breadcrumb end -->

<!-- search area -->
<div class="search-area search-common">
    <div class="container">
        <div class="search-wrapper">
            <div class="search-box hotel-search">
                <div class="search-form">
                    <form method="POST" action="{{ isset($search) ? route('hotel.search.update', $search) : route('hotel.search.submit') }}">
                        @csrf
                        @isset($search)
                            @foreach($activeFilters ?? [] as $filterKey => $filterValues)
                                @if(is_array($filterValues) && ! in_array($filterKey, ['sort', 'price_min', 'price_max', 'stars'], true))
                                    @foreach($filterValues as $filterValue)
                                        <input type="hidden" name="{{ $filterKey }}[]" value="{{ $filterValue }}">
                                    @endforeach
                                @endif
                            @endforeach
                            @if(!empty($activeFilters['stars']))
                                @foreach($activeFilters['stars'] as $star)
                                    <input type="hidden" name="stars[]" value="{{ $star }}">
                                @endforeach
                            @endif
                            @if(request()->has('price_min'))
                                <input type="hidden" name="price_min" value="{{ (int) $priceMin }}">
                            @endif
                            @if(request()->has('price_max'))
                                <input type="hidden" name="price_max" value="{{ (int) $priceMax }}">
                            @endif
                            <input type="hidden" name="sort" value="{{ $sort ?? 'default' }}">
                        @endisset
                        <div class="hotel-search-wrapper">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Destination</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="destination" class="form-control"
                                                value="{{ $search->destination ?? '' }}" placeholder="City, hotel or area" required>
                                            <i class="fal fa-earth-americas"></i>
                                        </div>
                                        @if(!empty($search?->destination))
                                        <p>{{ $search->destination }}</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="search-form-date">
                                            <div class="search-form-journey">
                                                <label>Check In</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="journey-date"
                                                        class="form-control date-picker journey-date"
                                                        value="{{ isset($search) ? $search->journey_date->format('Y-m-d') : '' }}" required>
                                                    <i class="fal fa-calendar-days"></i>
                                                </div>
                                                <p class="journey-day-name"></p>
                                            </div>
                                            <div class="search-form-return">
                                                <label>Check Out</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="return-date"
                                                        class="form-control date-picker return-date"
                                                        value="{{ isset($search) && $search->return_date ? $search->return_date->format('Y-m-d') : '' }}">
                                                </div>
                                                <p class="return-day-name"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group dropdown passenger-box">
                                        <div class="passenger-class" role="menu" data-bs-toggle="dropdown"
                                            aria-expanded="false">
                                            <label>Rooms, Guests</label>
                                            <div class="form-group-icon">
                                                <div class="passenger-total">
                                                    <span class="passenger-total-room">{{ $search->rooms ?? 1 }}</span> Rooms,
                                                    <span class="passenger-total-amount">{{ ($search->adult ?? 2) + ($search->children ?? 0) + ($search->infant ?? 0) }}</span> Guests
                                                </div>
                                                <i class="fal fa-user-tie-hair"></i>
                                            </div>
                                            <p class="passenger-class-name">{{ $search->room_type ?? 'Double Room' }}</p>
                                        </div>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <div class="dropdown-item">
                                                <div class="passenger-item">
                                                    <div class="passenger-info">
                                                        <h6>Adults</h6>
                                                        <p>12+ Years</p>
                                                    </div>
                                                    <div class="passenger-qty">
                                                        <button type="button" class="minus-btn"><i class="far fa-minus"></i></button>
                                                        <input type="text" name="adult" class="qty-amount passenger-adult"
                                                            value="{{ $search->adult ?? 2 }}" readonly>
                                                        <button type="button" class="plus-btn"><i class="far fa-plus"></i></button>
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
                                                        <button type="button" class="minus-btn"><i class="far fa-minus"></i></button>
                                                        <input type="text" name="children" class="qty-amount passenger-children"
                                                            value="{{ $search->children ?? 0 }}" readonly>
                                                        <button type="button" class="plus-btn"><i class="far fa-plus"></i></button>
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
                                                        <button type="button" class="minus-btn"><i class="far fa-minus"></i></button>
                                                        <input type="text" name="infant" class="qty-amount passenger-infant"
                                                            value="{{ $search->infant ?? 0 }}" readonly>
                                                        <button type="button" class="plus-btn"><i class="far fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-item">
                                                <div class="passenger-item">
                                                    <div class="passenger-info">
                                                        <h6>Rooms</h6>
                                                    </div>
                                                    <div class="passenger-qty">
                                                        <button type="button" class="minus-btn"><i class="far fa-minus"></i></button>
                                                        <input type="text" name="room" class="qty-amount passenger-room"
                                                            value="{{ $search->rooms ?? 1 }}" readonly>
                                                        <button type="button" class="plus-btn"><i class="far fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-item">
                                                <h6 class="mb-3 mt-2">Room Type</h6>
                                                <div class="passenger-class-info">
                                                    @foreach(['Single Room', 'Double Room', 'Deluxe Room'] as $type)
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            value="{{ $type }}" name="room-type"
                                                            id="room-type-{{ Str::slug($type) }}"
                                                            @checked(($search->room_type ?? 'Double Room') === $type)>
                                                        <label class="form-check-label" for="room-type-{{ Str::slug($type) }}">
                                                            {{ $type }}
                                                        </label>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="search-btn">
                                <button type="submit" class="theme-btn"><span class="far fa-search"></span>Search Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- search area end -->

<!-- hotel grid -->
<div class="hotel-grid py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-3 mb-4">
                @isset($search)
                    @include('pages.publicView.hotel.partials.hotel-filters', [
                        'search' => $search,
                        'filterGroups' => $filterGroups ?? [],
                        'facets' => $facets ?? [],
                        'activeFilters' => $activeFilters ?? [],
                        'filterAction' => $filterAction ?? route('hotel.search'),
                        'sort' => $sort ?? 'default',
                        'priceMin' => $priceMin ?? null,
                        'priceMax' => $priceMax ?? null,
                    ])
                @endisset
            </div>
            <div class="col-lg-8 col-xl-9">
                <div class="col-md-12">
                    <div class="booking-sort">
                        <h5>
                            @if(isset($resultsCount))
                                {{ number_format($resultsCount) }}@if(isset($totalResultsCount) && $totalResultsCount !== $resultsCount) of {{ number_format($totalResultsCount) }}@endif Hotels Found
                            @else
                                0 Hotels Found
                            @endif
                        </h5>
                        <div class="col-md-3 booking-sort-box">
                            <select class="select" id="hotel-sort" name="sort">
                                @foreach(\App\Services\HotelSearchResultsService::SORT_OPTIONS as $value => $label)
                                <option value="{{ $value }}" @selected(($sort ?? 'default') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @forelse(($results ?? []) as $result)
                        @include('pages.publicView.hotel.partials.hotel-result-item', ['result' => $result, 'search' => $search ?? null])
                    @empty
                        <div class="col-12">
                            <p class="text-center py-5">
                                @isset($search)
                                    No hotels match your filters. Adjust filters or update your search.
                                @else
                                    Search for hotels using the form above.
                                @endisset
                            </p>
                        </div>
                    @endforelse
                </div>
                @if(isset($resultsCount) && $resultsCount > 0)
                <div class="pagination-area mt-4">
                    <div class="pagination-showing">
                        <p>Showing {{ $resultsCount > 0 ? 1 : 0 }} - {{ $resultsCount }} of {{ $totalResultsCount ?? $resultsCount }} Hotels</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- hotel grid end -->

@endsection

@section('modal')

@endsection

@push('styles')
@isset($search)
<link rel="stylesheet" href="{{ asset('assets/css/flight-list-filters.css') }}?v={{ filemtime(public_path('assets/css/flight-list-filters.css')) }}">
@endisset
@endpush

@push('scripts')
@isset($search)
<script src="{{ asset('assets/js/hotel-list-filters.js') }}?v={{ filemtime(public_path('assets/js/hotel-list-filters.js')) }}"></script>
@endisset
@endpush
