<div class="fbw-card fbw-summary-sticky">
    <h4 class="fbw-card-title">Flight Summary</h4>
    <div class="fbw-summary-airline">
        <img src="{{ asset('assets/img/flight/airline-7.png') }}" alt="{{ $summary['airline'] }}">
        <div>
            <h5 class="mb-0">{{ $summary['airline'] }}</h5>
            <small class="text-muted">{{ $summary['flight_number'] }}</small>
        </div>
    </div>
    <div class="fbw-summary-route">
        <div class="text-center">
            <div class="airport-code">{{ $summary['from_code'] }}</div>
            <small class="text-muted">{{ $summary['from'] }}</small>
        </div>
        <div class="text-center flex-grow-1">
            <i class="fal fa-plane text-muted"></i>
            <div><small>{{ $summary['stops'] }} · {{ $summary['duration'] }}</small></div>
        </div>
        <div class="text-center">
            <div class="airport-code">{{ $summary['to_code'] }}</div>
            <small class="text-muted">{{ $summary['to'] }}</small>
        </div>
    </div>
    <dl class="fbw-summary-meta">
        <dt>Departure</dt>
        <dd>
            @if($summary['departure_date'] instanceof \Illuminate\Support\Carbon)
                {{ $summary['departure_date']->format('D, M d, Y') }}
            @else
                {{ $summary['departure_date'] }}
            @endif
            @if($summary['departure_time'] instanceof \Illuminate\Support\Carbon)
                · {{ $summary['departure_time']->format('H:i') }}
            @endif
        </dd>
        @if($summary['return_date'])
            <dt>Return</dt>
            <dd>
                @if($summary['return_date'] instanceof \Illuminate\Support\Carbon)
                    {{ $summary['return_date']->format('D, M d, Y') }}
                @else
                    {{ $summary['return_date'] }}
                @endif
            </dd>
        @endif
        @if($summary['arrival_time'] instanceof \Illuminate\Support\Carbon)
            <dt>Arrival</dt>
            <dd>{{ $summary['arrival_time']->format('D, M d, Y · H:i') }}</dd>
        @endif
        <dt>Trip Type</dt>
        <dd>{{ $summary['trip_type'] }}</dd>
        <dt>Cabin Class</dt>
        <dd>{{ $summary['cabin_class'] }}</dd>
        <dt>Passengers</dt>
        <dd>{{ $summary['adults'] }} Adult(s), {{ $summary['children'] }} Child(ren), {{ $summary['infants'] }} Infant(s)</dd>
    </dl>
    <div class="fbw-fare">
        <span>Estimated Fare</span>
        <span class="amount">{{ strtoupper($summary['currency']) }} {{ number_format($summary['estimated_fare'], 0) }}</span>
    </div>
</div>
