@extends('layouts.app')

@section('body-class', 'home-3')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/holiday-package-request-modal.css') }}?v={{ file_exists(public_path('assets/css/holiday-package-request-modal.css')) ? filemtime(public_path('assets/css/holiday-package-request-modal.css')) : time() }}">
@endpush

@section('content')

<x-catalog-list-hero
    :title="$items->total().' '.Str::plural('Package', $items->total()).' Found'"
    page="tourpackage"
>
    <x-slot:breadcrumb>
        <li><a href="{{ route('tourpackage') }}">Tour Packages</a></li>
        <li class="active">Search Results</li>
    </x-slot:breadcrumb>
    <x-slot:search>
        <div class="search-wrapper catalog-holiday-unified">
            <div class="search-box tour-search catalog-holiday-combined">
                <div id="holiday-package-request-wizard-wrap"
                    data-child-age-label="{{ __('holiday_package_request.child_age', ['number' => ':number']) }}"
                    data-error-message="{{ __('holiday_package_request.error') }}">
                    @include('partials.holiday-package-request.wizard', [
                        'locale' => app()->getLocale(),
                        'embedded' => true,
                    ])
                </div>

                <div class="catalog-hpr-divider" role="separator" aria-label="Package search">
                    <span class="catalog-hpr-divider-text">
                        <i class="far fa-search" aria-hidden="true"></i>
                        Search Available Packages
                    </span>
                </div>

                <div class="search-form catalog-hpr-package-search">
                    @include('partials.catalog.holiday-package-search-form', [
                        'preserveFilterInputs' => ['destination', 'q', 'page', 'holiday_type', 'travel_month', 'adult', 'children', 'infant', 'duration', 'min_price', 'max_price', 'sort', 'country'],
                    ])
                </div>
            </div>
        </div>
    </x-slot:search>
</x-catalog-list-hero>

<!-- tour grid -->
<div class="tour-grid catalog-list-results">
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
                    <div class="d-flex flex-wrap align-items-center gap-2">
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
                </div>

                <div class="row">
                    @forelse($items as $item)
                        <x-tour-package-card
                            :item="$item"
                            :route-prefix="$routePrefix ?? 'tourpackage'"
                            :search-query="$searchQuery ?? []"
                        />
                    @empty
                        <x-catalog-empty-state type="tourpackage" :search-query="$searchQuery ?? []" />
                    @endforelse
                </div>

                <x-catalog-quote-banner type="tourpackage" :search-query="$searchQuery ?? []" />

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

@push('scripts')
<script src="{{ asset('assets/js/holiday-package-request-modal.js') }}?v={{ file_exists(public_path('assets/js/holiday-package-request-modal.js')) ? filemtime(public_path('assets/js/holiday-package-request-modal.js')) : time() }}"></script>
@endpush
