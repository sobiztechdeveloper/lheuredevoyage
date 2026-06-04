@props(['summary' => []])

<div class="fbw-card fbw-summary-sticky">
    <h4 class="fbw-card-title">{{ $summary['title'] ?? 'Booking Summary' }}</h4>

    @if(!empty($summary['image']))
        <img src="{{ $summary['image'] }}" alt="" class="img-fluid rounded mb-3" style="max-height:140px;width:100%;object-fit:cover;">
    @endif

    @if(!empty($summary['headline']))
        <h5 class="mb-1" style="color:#162F65;">{{ $summary['headline'] }}</h5>
    @endif

    @if(!empty($summary['subtitle']))
        <p class="small text-muted mb-3"><i class="far fa-location-dot me-1"></i>{{ $summary['subtitle'] }}</p>
    @endif

    @if(!empty($summary['meta']))
        <dl class="fbw-summary-meta">
            @foreach($summary['meta'] as $label => $value)
                @if($value !== null && $value !== '')
                    <dt>{{ $label }}</dt>
                    <dd>{{ $value }}</dd>
                @endif
            @endforeach
        </dl>
    @endif

    @if(!empty($summary['fare']))
        <div class="fbw-fare">
            <span>{{ $summary['fare_label'] ?? 'Estimated Total' }}</span>
            <span class="amount">{{ $summary['fare'] }}</span>
        </div>
    @endif

    @if(!empty($summary['footnote']))
        <p class="small text-muted mt-3 mb-0">{{ $summary['footnote'] }}</p>
    @endif
</div>
