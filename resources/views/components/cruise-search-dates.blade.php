@php
    $journeyValue = old('journey-date', request('journey-date', request('departure_date', '')));
    $returnValue = old('return-date', request('return-date', request('return_date', '')));
    $formatForPicker = static function ($value) {
        if (! $value || ! preg_match('/^\d{4}-\d{2}-\d{2}$/', $value)) {
            return $value;
        }

        return \Carbon\Carbon::parse($value)->format('n/j/Y');
    };
    $journeyValue = $formatForPicker($journeyValue);
    $returnValue = $formatForPicker($returnValue);
@endphp

<div class="search-form-date">
    <div class="search-form-journey">
        <label>Sailing From</label>
        <div class="form-group-icon">
            <input type="text" name="journey-date" autocomplete="off"
                class="form-control date-picker journey-date"
                value="{{ $journeyValue }}" aria-label="Sailing from">
            <i class="fal fa-calendar-days"></i>
        </div>
        <p class="journey-day-name"></p>
    </div>
    <div class="search-form-return">
        <label>Sailing To</label>
        <div class="form-group-icon">
            <input type="text" name="return-date" autocomplete="off"
                class="form-control date-picker return-date"
                value="{{ $returnValue }}" aria-label="Sailing to">
            <i class="fal fa-calendar-days"></i>
        </div>
        <p class="return-day-name"></p>
    </div>
</div>
