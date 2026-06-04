@extends('layouts.app')
@section('body-class', 'home-3')
@section('content')
<x-site-breadcrumb :title="$item->displayName()" page="cruise" />
<div class="py-80">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-8">
                @if($item->image_url && !str_contains($item->image_url, 'logo.png'))
                <img src="{{ $item->image_url }}" class="w-100 rounded mb-4" alt="{{ $item->displayName() }}" style="max-height:400px;object-fit:cover;">
                @endif
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <p class="text-muted mb-1">{{ $item->cruise_line }} · {{ $item->ship_name }}</p>
                    <h1 class="h3">{{ $item->displayName() }}</h1>
                    <p class="mb-2"><i class="far fa-map-marker-alt me-1"></i> {{ $item->departure_port }} → {{ $item->arrival_port }}</p>
                    <span class="badge bg-primary">{{ $item->regionLabel() }}</span>
                    @if($item->short_description)<p class="lead mt-3">{{ $item->short_description }}</p>@endif
                    @if($item->description)<div class="mt-3">{!! nl2br(e($item->description)) !!}</div>@endif
                </div>
                @if($item->itineraryDays->isNotEmpty())
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h4 class="h5 mb-3">Itinerary</h4>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead><tr><th>Day</th><th>Port</th><th>Arrival</th><th>Departure</th></tr></thead>
                            <tbody>
                            @foreach($item->itineraryDays as $day)
                                <tr>
                                    <td>{{ $day->day_number }}</td>
                                    <td><strong>{{ $day->port_name }}</strong>@if($day->description)<br><small class="text-muted">{{ $day->description }}</small>@endif</td>
                                    <td>{{ $day->arrival_time ?: '—' }}</td>
                                    <td>{{ $day->departure_time ?: '—' }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
                @if($item->cabins->isNotEmpty())
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h4 class="h5 mb-3">Cabin types</h4>
                    <div class="row g-3">
                        @foreach($item->cabins as $cabin)
                        <div class="col-md-6">
                            <div class="border rounded p-3 h-100">
                                <h5 class="h6">{{ $cabin->name }} <span class="badge bg-light text-dark">{{ $cabin->cabinTypeLabel() }}</span></h5>
                                <p class="small text-muted mb-2">{{ $cabin->description }}</p>
                                <p class="mb-0"><strong>From {{ $cabin->formattedPrice() }}</strong> · max {{ $cabin->max_occupancy }} guests</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
                @if(!empty($item->included_services))
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h4 class="h5">Included</h4>
                    <ul class="mb-0">@foreach($item->included_services as $key)<li>{{ $includedOptions[$key] ?? $key }}</li>@endforeach</ul>
                </div>
                @endif
                @if(!empty($item->not_included_services))
                <div class="card border-0 shadow-sm p-4 mb-4">
                    <h4 class="h5">Not included</h4>
                    <ul class="mb-0">@foreach($item->not_included_services as $key)<li>{{ $notIncludedOptions[$key] ?? $key }}</li>@endforeach</ul>
                </div>
                @endif
                <div class="d-flex flex-wrap gap-2">
                    @if($item->brochureUrl())<a href="{{ $item->brochureUrl() }}" class="btn btn-outline-primary btn-sm" target="_blank">Brochure</a>@endif
                    @if($item->deckPlanUrl())<a href="{{ $item->deckPlanUrl() }}" class="btn btn-outline-primary btn-sm" target="_blank">Deck plan</a>@endif
                    @if($item->termsPdfUrl())<a href="{{ $item->termsPdfUrl() }}" class="btn btn-outline-primary btn-sm" target="_blank">Terms</a>@endif
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4 sticky-top" style="top:100px;">
                    <p class="text-muted mb-1">From</p>
                    <h3 class="text-primary">{{ $item->startingPriceDisplay() }}</h3>
                    <p class="small text-muted">Indicative cruise fare · {{ $item->duration_nights ?? $item->duration_days }} nights</p>
                    <a href="{{ route('cruise.quote.wizard', ['cruise' => $item->slug]) }}" class="theme-btn w-100 text-center mb-2">Request Cruise Quote</a>
                    <a href="{{ route('cruise') }}" class="btn btn-outline-secondary w-100">Browse all cruises</a>
                </div>
                @if($related->isNotEmpty())
                <div class="card border-0 shadow-sm p-4 mt-4">
                    <h5>Related cruises</h5>
                    @foreach($related as $rel)
                        <a href="{{ route('cruise.show', $rel->slug) }}" class="d-block mb-2">{{ $rel->displayName() }}</a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
