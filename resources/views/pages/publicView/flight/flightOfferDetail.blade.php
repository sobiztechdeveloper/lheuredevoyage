@extends('layouts.app')

@section('body-class', 'home-3')

@section('content')
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">Flight Details</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li><a href="{{ route('flight.search.results', $search) }}">Results</a></li>
            <li class="active">{{ $offer->airline }}</li>
        </ul>
    </div>
</div>

<div class="flight-list py-120">
    <div class="container">
        <div class="card p-4 mb-4">
            <div class="d-flex flex-wrap justify-content-between gap-3">
                <div>
                    <h3>{{ $offer->airline }} @if($offer->flight_number)— {{ $offer->flight_number }}@endif</h3>
                    <p class="mb-0">{{ $offer->from_destination }} → {{ $offer->to_destination }}</p>
                    <small class="text-muted">
                        @if($offer->departure_at){{ $offer->departure_at->format(config('date.display_datetime')) }}@endif
                        @if($offer->arrival_at) — {{ $offer->arrival_at->format('H:i') }}@endif
                        | {{ $offer->duration }}
                        | {{ $offer->stops === 0 ? 'Non-stop' : $offer->stops.' stop(s)' }}
                        | {{ ucfirst(str_replace('_', ' ', $offer->cabin_class)) }}
                    </small>
                </div>
                <div class="text-end">
                    <h3 class="mb-0">${{ number_format($offer->price, 0) }}</h3>
                    <span class="text-muted">{{ strtoupper($offer->currency) }}</span>
                </div>
            </div>
        </div>

        @if($offer->summary)
            <div class="card p-4 mb-4">
                <h5>Itinerary summary</h5>
                <pre class="bg-light p-3 rounded small mb-0" style="max-height:320px;overflow:auto;">{{ json_encode($offer->summary, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            </div>
        @endif

        <div class="d-flex gap-2 flex-wrap">
            <a href="{{ route('flight.search.results', $search) }}" class="theme-btn style-outline">Back to results</a>
            <a href="{{ route('flight.search.offer.fare-rules', [$search, $offer->external_offer_id]) }}" class="theme-btn">View fare rules</a>
        </div>
    </div>
</div>
@endsection
