@extends('layouts.app')

@section('body-class', 'home-3')

@section('content')

@php
    $travelerTotal = max(1, (int) request('adult', 2) + (int) request('children', 0) + (int) request('infant', 0));
@endphp

<x-site-breadcrumb :title="$items->total().' '.Str::plural('Cruise', $items->total()).' Found'" page="cruise">
    <li><a href="{{ route('cruise') }}">Cruises</a></li>
    <li class="active">Search Results</li>
</x-site-breadcrumb>

<div class="search-area search-common">
    <div class="container">
        <div class="search-wrapper">
            <div class="search-box cruise-search">
                <div class="search-form">
                    <form method="GET" action="{{ route('cruise.search') }}">
                        <x-catalog-search-preserved-inputs :except="['destination', 'q', 'page', 'adult', 'children', 'infant', 'journey-date', 'return-date', 'cruise_line', 'sort', 'min_price', 'max_price', 'region', 'duration', 'cabin_type', 'featured_only', 'categories', 'facilities']" />
                        <div class="cruise-search-wrapper">
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <label>Destination</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="destination" class="form-control"
                                                value="{{ $activeDestination ?? '' }}" placeholder="Region or port">
                                            <i class="fal fa-earth-americas"></i>
                                        </div>
                                        <p>Where Are You Going?</p>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-cruise-search-dates />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-cruise-search-line :cruise-line-options="$cruiseLineOptions ?? null" />
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group dropdown passenger-box">
                                        <div class="passenger-class" role="menu" data-bs-toggle="dropdown" aria-expanded="false">
                                            <label>Travelers</label>
                                            <div class="form-group-icon">
                                                <div class="passenger-total">
                                                    <span class="passenger-total-amount">{{ $travelerTotal }}</span>
                                                    Travelers
                                                </div>
                                                <i class="fal fa-user-tie-hair"></i>
                                            </div>
                                            <p class="passenger-class-name">In Cabin</p>
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
                                                            value="{{ request('adult', 2) }}" readonly>
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
                                                            value="{{ request('children', 0) }}" readonly>
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
                                                            value="{{ request('infant', 0) }}" readonly>
                                                        <button type="button" class="plus-btn"><i class="far fa-plus"></i></button>
                                                    </div>
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

<div class="cruise-grid py-120">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-3 mb-4">
                @include('partials.catalog.cruise-filters-sidebar', [
                    'filterGroups' => $filterGroups ?? [],
                    'regionOptions' => $regionOptions ?? [],
                    'durationOptions' => $durationOptions ?? [],
                    'cabinTypeOptions' => $cabinTypeOptions ?? [],
                    'priceBounds' => $priceBounds ?? ['min' => 0, 'max' => 5000],
                    'activeMinPrice' => $activeMinPrice ?? null,
                    'activeMaxPrice' => $activeMaxPrice ?? null,
                ])
            </div>
            <div class="col-lg-8 col-xl-9">
                <div class="booking-sort mb-4 d-flex flex-wrap justify-content-between align-items-center gap-3">
                    <h5 class="mb-0">{{ $items->total() }} {{ Str::plural('Cruise', $items->total()) }} Found</h5>
                    <form method="GET" action="{{ route('cruise.search') }}" class="booking-sort-box" style="min-width:220px;">
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
                            <option value="name" @selected(request('sort') === 'name')>Cruise Name</option>
                        </select>
                    </form>
                </div>

                <x-cruise-list-results :items="$items" :search-query="$searchQuery ?? []" :show-header="false" />

                <div class="text-center mt-4 p-4 rounded" style="background:linear-gradient(135deg,#162F65,#3361AC);color:#fff;">
                    <h4 class="text-white mb-2">Need a personalised cruise quote?</h4>
                    <p class="mb-3 opacity-75">Our consultants will prepare sailing options and cabin pricing with no obligation.</p>
                    @php
                        $wizardCtaUrl = route('cruise.quote.wizard');
                        if (! empty($searchQuery ?? [])) {
                            $wizardCtaUrl .= '?'.http_build_query($searchQuery);
                        }
                    @endphp
                    <a href="{{ $wizardCtaUrl }}" class="theme-btn" style="background:#F8B501;border-color:#F8B501;color:#162F65;">Request Cruise Quote</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function () {
    function syncCruiseTravelerTotal(box) {
        var adult = parseInt(box.find('.passenger-adult').val(), 10) || 0;
        var children = parseInt(box.find('.passenger-children').val(), 10) || 0;
        var infant = parseInt(box.find('.passenger-infant').val(), 10) || 0;
        box.find('.passenger-total-amount').text(Math.max(1, adult + children + infant));
    }
    $('.cruise-search .passenger-box').each(function () {
        var box = $(this);
        box.find('.plus-btn, .minus-btn').on('click', function () {
            setTimeout(function () { syncCruiseTravelerTotal(box); }, 0);
        });
    });
})();
</script>
@endpush
