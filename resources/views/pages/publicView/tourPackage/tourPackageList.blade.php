@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

<x-site-breadcrumb :title="$items->total().' '.Str::plural('Package', $items->total()).' Found'" page="tourpackage">
    <li><a href="{{ route('tourpackage') }}">Tour Packages</a></li>
    <li class="active">Search Results</li>
</x-site-breadcrumb>

<!-- search area -->
<div class="search-area search-common">
    <div class="container">
        <div class="search-wrapper">
            <div class="search-box tour-search">
                <div class="search-form">
                    @include('partials.catalog.holiday-package-search-form', [
                        'preserveFilterInputs' => ['destination', 'q', 'page', 'holiday_type', 'travel_month', 'adult', 'children', 'infant', 'duration', 'min_price', 'max_price', 'sort', 'country'],
                    ])
                </div>
            </div>
        </div>
    </div>
</div>
<!-- search area end -->

<!-- tour grid -->
<div class="tour-grid py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-3 mb-4">
                @include('partials.catalog.tour-package-filters-sidebar', [
                    'countryOptions' => $countryOptions ?? [],
                    'holidayTypeOptions' => $holidayTypeOptions ?? [],
                    'durationOptions' => $durationOptions ?? [],
                    'priceBounds' => $priceBounds ?? ['min' => 0, 'max' => 5000],
                    'activeMinPrice' => $activeMinPrice ?? 0,
                    'activeMaxPrice' => $activeMaxPrice ?? 5000,
                ])
            </div>
            <div class="col-lg-8 col-xl-9">
                <div class="booking-sort mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="mb-0">{{ $items->total() }} {{ Str::plural('Package', $items->total()) }} Found</h5>
                    <form method="GET" action="{{ route('tourpackage') }}" class="booking-sort-box" style="min-width:220px;">
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
                            <option value="name" @selected(request('sort') === 'name')>Package Name</option>
                        </select>
                    </form>
                </div>

                <div class="row">
                    @forelse($items as $item)
                        <x-tour-package-card
                            :item="$item"
                            :route-prefix="$routePrefix ?? 'tourpackage'"
                            :search-query="$searchQuery ?? []"
                        />
                    @empty
                        <div class="col-12">
                            <p class="text-center py-5">No packages found. Try adjusting your search or filters.</p>
                        </div>
                    @endforelse
                </div>

                @if($items->hasPages())
                    <div class="pagination-area mt-4">
                        {{ $items->withQueryString()->links() }}
                        <div class="pagination-showing">
                            <p>Showing {{ $items->firstItem() }} - {{ $items->lastItem() }} of {{ $items->total() }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
<!-- tour grid end -->

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection
