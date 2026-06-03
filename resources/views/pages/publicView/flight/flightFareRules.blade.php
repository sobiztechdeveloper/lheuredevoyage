@extends('layouts.app')

@section('body-class', 'home-3')

@section('content')
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">Fare Rules</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('flight.search.results', $search) }}">Results</a></li>
            <li><a href="{{ route('flight.search.offer', [$search, $offer->external_offer_id]) }}">Flight</a></li>
            <li class="active">Fare Rules</li>
        </ul>
    </div>
</div>

<div class="flight-list py-120">
    <div class="container">
        <div class="card p-4">
            <h4 class="mb-3">{{ $offer->airline }} — {{ $offer->from_destination }} to {{ $offer->to_destination }}</h4>

            @if(!empty($fareRules['rules']))
                <ul class="list-group list-group-flush mb-4">
                    @foreach($fareRules['rules'] as $rule)
                        <li class="list-group-item">
                            @if(is_array($rule))
                                <strong>{{ $rule['title'] ?? $rule['category'] ?? 'Rule' }}</strong>
                                <p class="mb-0">{{ $rule['text'] ?? $rule['description'] ?? json_encode($rule) }}</p>
                            @else
                                {{ $rule }}
                            @endif
                        </li>
                    @endforeach
                </ul>
            @elseif(!empty($fareRules['message']))
                <p class="text-muted">{{ $fareRules['message'] }}</p>
            @else
                <pre class="bg-light p-3 rounded small" style="max-height:480px;overflow:auto;">{{ json_encode($fareRules['raw'] ?? $fareRules, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
            @endif

            <a href="{{ route('flight.search.offer', [$search, $offer->external_offer_id]) }}" class="theme-btn style-outline">Back to flight</a>
        </div>
    </div>
</div>
@endsection
