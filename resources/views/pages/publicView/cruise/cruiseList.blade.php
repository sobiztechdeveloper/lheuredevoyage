@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/08.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">2,350 Results Found</h2>
        <ul class="breadcrumb-menu">
            <li><a href="index.html">Home</a></li>
            <li class="active">Cruise Search</li>
        </ul>
    </div>
</div>
<!-- breadcrumb end -->


<!-- search area -->
<div class="search-area search-common">
    <div class="container">
        <div class="search-wrapper">
            <!-- cruise search -->
            <div class="search-box cruise-search">
                <div class="search-form">
                    <form action="#">
                        <div class="cruise-search-wrapper">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label>Destination</label>
                                        <div class="form-group-icon">
                                            <input type="text" name="destination" class="form-control"
                                                value="New York, United States">
                                            <i class="fal fa-earth-americas"></i>
                                        </div>
                                        <p>Where Are You Going?</p>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="search-form-date">
                                            <div class="search-form-journey">
                                                <label>Journey Date</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="journey-date"
                                                        class="form-control date-picker journey-date">
                                                    <i class="fal fa-calendar-days"></i>
                                                </div>
                                                <p class="journey-day-name"></p>
                                            </div>
                                            <div class="search-form-return">
                                                <label>Return Date</label>
                                                <div class="form-group-icon">
                                                    <input type="text" name="return-date"
                                                        class="form-control date-picker return-date">
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
                                            <label>Travelers</label>
                                            <div class="form-group-icon">
                                                <div class="passenger-total">
                                                    <span class="passenger-total-amount">2</span>
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
                                                        <button type="button" class="minus-btn"><i
                                                                class="far fa-minus"></i></button>
                                                        <input type="text" name="adult"
                                                            class="qty-amount passenger-adult" value="2"
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
                                                            value="0" readonly>
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
                                                            value="0" readonly>
                                                        <button type="button" class="plus-btn"><i
                                                                class="far fa-plus"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown-item">
                                                <h6 class="mb-3 mt-2">Cruise Class</h6>
                                                <div class="passenger-class-info">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            value="In Cabin" name="cruise-class"
                                                            id="cruise-class1">
                                                        <label class="form-check-label"
                                                            for="cruise-class1">
                                                            In Cabin
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" checked
                                                            type="radio" value="In Chair"
                                                            name="cruise-class" id="cruise-class2">
                                                        <label class="form-check-label"
                                                            for="cruise-class2">
                                                            In Chair
                                                        </label>
                                                    </div>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio"
                                                            value="In First Class" name="cruise-class"
                                                            id="cruise-class3">
                                                        <label class="form-check-label"
                                                            for="cruise-class3">
                                                            In First Class
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
            <!-- cruise search end -->
        </div>
    </div>
</div>
<!-- search area end -->


<!-- cruise grid -->
<div class="cruise-grid py-120">
    <div class="container">
        <div class="row">
            <!-- cruise booking sidebar -->
            <div class="col-lg-4 col-xl-3 mb-4">
                @include('partials.catalog.filters-sidebar', ['filterGroups' => $filterGroups ?? []])
                <div class="booking-sidebar">
                    <div class="booking-item">
                        <h4 class="booking-title">Cruise Price</h4>
                        <div class="cruise-price">
                            <div class="price-range-slider">
                                <div class="price-range-info">
                                    <label for="priceRange1">Price:</label>
                                    <input type="text" class="priceRange" id="priceRange1" readonly>
                                </div>
                                <div id="price-range1" class="price-range slider"></div>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Cruise Duration</h4>
                        <div class="cruise-duration">
                            <div class="form-check">
                                <input class="form-check-input" name="cruise-duration" type="checkbox" value="1"
                                    id="cruise-duration1">
                                <label class="form-check-label" for="cruise-duration1">
                                    1-5 days <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="cruise-duration" type="checkbox" value="2"
                                    id="cruise-duration2">
                                <label class="form-check-label" for="cruise-duration2">
                                    6-15 days <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="cruise-duration" type="checkbox" value="3"
                                    id="cruise-duration3">
                                <label class="form-check-label" for="cruise-duration3">
                                    15-25 days <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="cruise-duration" type="checkbox" value="4"
                                    id="cruise-duration4">
                                <label class="form-check-label" for="cruise-duration4">
                                    1 Month <span>(25)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Languages</h4>
                        <div class="cruise-language">
                            <div class="form-check">
                                <input class="form-check-input" name="cruise-language" type="checkbox" value="1"
                                    id="cruise-language1">
                                <label class="form-check-label" for="cruise-language1">
                                    English <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="cruise-language" type="checkbox" value="2"
                                    id="cruise-language2">
                                <label class="form-check-label" for="cruise-language2">
                                    Spanish <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="cruise-language" type="checkbox" value="3"
                                    id="cruise-language3">
                                <label class="form-check-label" for="cruise-language3">
                                    French <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="cruise-language" type="checkbox" value="4"
                                    id="cruise-language4">
                                <label class="form-check-label" for="cruise-language4">
                                    Turkish <span>(25)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="booking-item">
                        <h4 class="booking-title">Cabin Type</h4>
                        <div class="cabin-type">
                            <div class="form-check">
                                <input class="form-check-input" name="cabin-type" type="checkbox"
                                    value="1" id="cabin-type1">
                                <label class="form-check-label" for="cabin-type1">
                                    Inside <span>(20)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="cabin-type" type="checkbox"
                                    value="2" id="cabin-type2">
                                <label class="form-check-label" for="cabin-type2">
                                    Outside <span>(15)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="cabin-type" type="checkbox"
                                    value="3" id="cabin-type3">
                                <label class="form-check-label" for="cabin-type3">
                                    Suite <span>(18)</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" name="cabin-type" type="checkbox"
                                    value="4" id="cabin-type4">
                                <label class="form-check-label" for="cabin-type4">
                                    Balcony <span>(18)</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- cruise booking grid -->
            <div class="col-lg-8 col-xl-9">
                <div class="col-md-12">
                    <div class="booking-sort">
                        <h5>2,350 Results Found</h5>
                        <div class="col-md-3 booking-sort-box">
                            <select class="select">
                                <option value="1">Sort By Default</option>
                                <option value="2">Sort By Popular</option>
                                <option value="3">Sort By Low Price</option>
                                <option value="4">Sort By High Price</option>
                            </select>
                        </div>
                    </div>
                </div>
                @include('components.catalog-list-results', ['items' => $items, 'routePrefix' => $routePrefix ?? 'cruise', 'label' => 'Cruises'])
</div>
            </div>
        </div>
    </div>
</div>
<!-- cruise grid end -->

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection