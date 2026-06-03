@extends('layouts.app')

@section('body-class', 'home-3')

@section('content')
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">Flight Results</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="active">Flight Results</li>
        </ul>
    </div>
</div>

<div class="flight-list py-120">
    <div class="container">
        <div class="mb-4">
            <h4>{{ ucfirst(str_replace('_', ' ', $search->trip_type)) }}: {{ $search->from_destination }} → {{ $search->to_destination }}</h4>
            <p class="mb-0">
                Depart: {{ $search->journey_date->format('M d, Y') }}
                @if($search->return_date)
                    | Return: {{ $search->return_date->format('M d, Y') }}
                @endif
                | {{ $search->adult }} Adult(s), {{ $search->children }} Child(ren), {{ $search->infant }} Infant(s)
                | {{ ucfirst(str_replace('_', ' ', $search->cabin_class)) }}
                @if($search->usesAerticket())
                    <span class="badge bg-primary ms-2">AERTiCKET</span>
                @endif
            </p>
        </div>

        <div class="row g-4">
            @forelse($results as $result)
                <div class="col-12">
                    <div class="card p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                            <div>
                                <h5 class="mb-1">{{ $result->airline }} — {{ $result->flight_number }}</h5>
                                <p class="mb-0">{{ $result->from_destination }} → {{ $result->to_destination }}</p>
                                <small class="text-muted">
                                    @if($result->departure_at){{ $result->departure_at->format('H:i') }}@endif
                                    @if($result->arrival_at) - {{ $result->arrival_at->format('H:i') }}@endif
                                    | {{ $result->duration }}
                                    | {{ $result->stops === 0 ? 'Non-stop' : $result->stops.' stop(s)' }}
                                </small>
                            </div>
                            <div class="text-end">
                                <h4 class="mb-0">${{ number_format($result->price, 0) }}</h4>
                                <span class="text-muted">{{ strtoupper($result->currency) }}</span>
                                @if(($usesAerticket ?? false) && $result->external_offer_id)
                                    <div class="mt-2">
                                        <a href="{{ route('flight.search.offer', [$search, $result->external_offer_id]) }}" class="theme-btn btn-sm">Details &amp; Fare Rules</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-warning">No flights found for this search. Try different dates or destinations.</div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
