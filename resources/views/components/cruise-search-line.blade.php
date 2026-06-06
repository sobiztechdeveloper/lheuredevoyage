@props([
    'cruiseLineOptions' => null,
])

@php
    $search = app(\App\Services\CruiseSearchService::class);
    $cruiseLineOptions = $cruiseLineOptions ?? ['' => 'Any Line'] + app(\App\Services\CruiseLineService::class)->activeOptions();
    if (count($cruiseLineOptions) <= 1) {
        $cruiseLineOptions = $search->cruiseLineOptions();
    }
    $selectedLine = old('cruise_line', request('cruise_line', ''));
@endphp

<label>Cruise Line</label>
<div class="form-group-icon">
    <select name="cruise_line" class="form-control" aria-label="Cruise line">
        @foreach($cruiseLineOptions as $value => $label)
            <option value="{{ $value }}" @selected((string) $selectedLine === (string) $value)>{{ $label }}</option>
        @endforeach
    </select>
    <i class="fal fa-ship"></i>
</div>
<p>Any cruise line</p>
