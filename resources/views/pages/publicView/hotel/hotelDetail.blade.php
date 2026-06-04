@extends('layouts.app')
@section('body-class', 'home-3')
@section('content')
@php
    $gallery = collect($hotel->gallery_json ?? [])->map(function ($path) {
        if (str_starts_with((string) $path, 'http') || str_starts_with((string) $path, 'assets/')) {
            return asset($path);
        }
        return asset('storage/'.$path);
    })->all();
    $hero = $hotel->image_url;
    $bookingQuery = $searchParams ?? [];
    if (request()->filled('room_id')) {
        $bookingQuery['room_id'] = request('room_id');
    }
@endphp
<div class="site-breadcrumb" style="background: url({{ asset('assets/img/breadcrumb/01.jpg') }})">
    <div class="container">
        <h2 class="breadcrumb-title">{{ $hotel->name }}</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('hotel.search') }}">Hotels</a></li>
            <li class="active">{{ $hotel->name }}</li>
        </ul>
    </div>
</div>
<section class="hotel-single py-80">
    <div class="container">
        <div class="row g-4">
                <div class="col-lg-8">
                <div class="listing-slider owl-carousel owl-theme mb-4">
                    <img src="{{ $hero }}" alt="{{ $hotel->name }}" class="w-100 rounded" style="max-height:420px;object-fit:cover">
                    @foreach($gallery as $img)
                        <img src="{{ $img }}" alt="" class="w-100 rounded" style="max-height:420px;object-fit:cover">
                    @endforeach
                        </div>
                <div class="mb-4">
                    <h3>{{ $hotel->name }}</h3>
                    <p class="text-muted mb-2"><i class="far fa-location-dot me-1"></i>{{ $hotel->location }}</p>
                    @if($hotel->starCount())
                        <span class="text-warning">@for($i=0;$i<$hotel->starCount();$i++)<i class="fas fa-star"></i>@endfor</span>
                    @endif
                    @if($hotel->rating)<span class="ms-2">Guest rating {{ $hotel->rating }}/5 ({{ $hotel->review_count }} reviews)</span>@endif
                    @if($hotel->short_description)<p class="mt-3">{{ $hotel->short_description }}</p>@endif
                            </div>
                @php
                    $highlights = $hotel->facilities->merge($hotel->sports)->merge($hotel->wellnesses)->merge($hotel->beachTypes);
                @endphp
                @if($highlights->isNotEmpty())
                <div class="mb-4">
                    <h5>Property Highlights</h5>
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($highlights as $item)
                            <span class="badge rounded-pill" style="background:#162F65;">{{ $item->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
                @if($hotel->description)
                <div class="mb-4">
                    <h5>About Property</h5>
                    <div>{!! nl2br(e($hotel->description)) !!}</div>
                </div>
                @endif
                <div class="mb-4">
                    <h5>Facilities</h5>
                    @if($hotel->facilities->isNotEmpty())<p><strong>Hotel:</strong> {{ $hotel->facilities->pluck('name')->join(', ') }}</p>@endif
                    @if($hotel->wellnesses->isNotEmpty())<p><strong>Wellness:</strong> {{ $hotel->wellnesses->pluck('name')->join(', ') }}</p>@endif
                    @if($hotel->sports->isNotEmpty())<p><strong>Sports:</strong> {{ $hotel->sports->pluck('name')->join(', ') }}</p>@endif
                    @if($hotel->beachTypes->isNotEmpty())<p><strong>Beach:</strong> {{ $hotel->beachTypes->pluck('name')->join(', ') }}</p>@endif
                    @if($hotel->roomFacilities->isNotEmpty())<p><strong>Room:</strong> {{ $hotel->roomFacilities->pluck('name')->join(', ') }}</p>@endif
                                </div>
                <div class="mb-4">
                    <h5>Availability</h5>
                    @forelse($hotel->activeRooms as $room)
                    <div class="card mb-3 border-0 shadow-sm">
                        <div class="card-body row align-items-center g-3">
                            <div class="col-md-3">
                                <img src="{{ $room->image_url ?? $hero }}" alt="" class="img-fluid rounded">
                            </div>
                            <div class="col-md-6">
                                <h5 class="mb-1">{{ $room->name }}</h5>
                                <p class="small text-muted mb-1">{{ $room->room_type }} · {{ $room->maxOccupancyLabel() }} · {{ $room->bed_type }}</p>
                                @if($room->meal_plan)<p class="small mb-1">Meal: {{ $room->meal_plan }}</p>@endif
                                @if($room->features)<p class="small mb-0">{{ implode(' · ', $room->features) }}</p>@endif
                            </div>
                            <div class="col-md-3 text-md-end">
                                <h5 class="mb-2" style="color:#E8AF30;">${{ number_format($room->price, 0) }}<small class="text-muted">/night</small></h5>
                                <a href="{{ route('hotel.booking.create', ['hotel' => $hotel, 'room_id' => $room->id] + $bookingQuery) }}" class="theme-btn btn-sm">Request Booking</a>
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-muted">No rooms listed. <a href="{{ route('hotel.booking.create', ['hotel' => $hotel] + $bookingQuery) }}">Request booking</a> for this property.</p>
                    @endforelse
                            </div>
                        </div>
            <div class="col-lg-4">
                <div class="p-4 rounded shadow-sm sticky-top" style="top:100px;background:#f8fafd;border:1px solid #e2e8f0;">
                    <h5 style="color:#162F65;">Book This Hotel</h5>
                    <p class="small text-muted">From <strong style="color:#E8AF30;">${{ number_format($hotel->price, 0) }}</strong> per night</p>
                    <a href="{{ route('hotel.booking.create', ['hotel' => $hotel] + $bookingQuery) }}" class="theme-btn w-100 text-center">Request Booking</a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
