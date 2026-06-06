@props([
    'monthOptions' => null,
])

@php
    $search = app(\App\Services\CruiseSearchService::class);
    $monthOptions = $monthOptions ?? $search->departureMonthOptions();
    $selectedMonth = old('departure_month', request('departure_month', request('journey-date', '')));
    if ($selectedMonth && preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedMonth)) {
        $selectedMonth = \Carbon\Carbon::parse($selectedMonth)->format('Y-m');
    }
@endphp

<label>Departure Month</label>
<div class="form-group-icon">
    <select name="departure_month" class="form-control" aria-label="Departure month">
        @foreach($monthOptions as $value => $label)
            <option value="{{ $value }}" @selected((string) $selectedMonth === (string) $value)>{{ $label }}</option>
        @endforeach
    </select>
    <i class="fal fa-calendar-days"></i>
</div>
<p>When would you like to sail?</p>
