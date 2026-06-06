@php
    $tourSearch = app(\App\Services\TourPackageSearchService::class);
    $countryOptions = $countryOptions ?? $tourSearch->countryOptions();
    $holidayTypeOptions = $holidayTypeOptions ?? $tourSearch->holidayTypeOptions();
    $travelMonthOptions = $travelMonthOptions ?? $tourSearch->travelMonthOptions();
    $selectedCountry = request('destination', request('q', ''));
    $selectedHolidayType = request('holiday_type', '');
    $selectedTravelMonth = request('travel_month', '');
    $adultCount = (int) request('adult', 2);
    $childrenCount = (int) request('children', 0);
    $infantCount = (int) request('infant', 0);
@endphp

<form method="GET" action="{{ route('tourpackage') }}">
    @if(!empty($preserveFilterInputs))
        <x-catalog-search-preserved-inputs :except="$preserveFilterInputs" />
    @endif
    <div class="holiday-search-wrapper">
        <div class="flight-search-item">
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Destination</label>
                        <div class="form-group-icon">
                            <select name="destination" class="form-control" aria-label="Destination country">
                                @foreach($countryOptions as $value => $label)
                                    <option value="{{ $value }}" @selected((string) $selectedCountry === (string) $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="fal fa-earth-americas"></i>
                        </div>
                        <p class="destination-field-meta" aria-hidden="true">&nbsp;</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Holiday Type</label>
                        <div class="form-group-icon">
                            <select name="holiday_type" class="form-control" aria-label="Holiday type">
                                @foreach($holidayTypeOptions as $value => $label)
                                    <option value="{{ $value }}" @selected((string) $selectedHolidayType === (string) $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="fal fa-umbrella-beach"></i>
                        </div>
                        <p class="destination-field-meta" aria-hidden="true">&nbsp;</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Travel Month</label>
                        <div class="form-group-icon">
                            <select name="travel_month" class="form-control" aria-label="Travel month">
                                @foreach($travelMonthOptions as $value => $label)
                                    <option value="{{ $value }}" @selected((string) $selectedTravelMonth === (string) $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <i class="fal fa-calendar-days"></i>
                        </div>
                        <p class="destination-field-meta" aria-hidden="true">&nbsp;</p>
                    </div>
                </div>
                <div class="col-lg-3">
                    <x-catalog-traveler-picker
                        :adult="$adultCount"
                        :children="$childrenCount"
                        :infant="$infantCount"
                    />
                </div>
            </div>
        </div>
        <div class="search-btn">
            <button type="submit" class="theme-btn"><span class="far fa-search"></span>Search Now</button>
        </div>
    </div>
</form>
