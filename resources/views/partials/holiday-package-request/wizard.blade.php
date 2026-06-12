@php
    $config = is_array($config ?? null) ? $config : holiday_package_request_config();
    $optionLabels = $config['option_labels'] ?? [];
    $embedded = (bool) ($embedded ?? false);
    $wizardSteps = [
        ['id' => 1, 'label' => __('holiday_package_request.sections.travel')],
        ['id' => 2, 'label' => 'Travellers'],
        ['id' => 3, 'label' => __('holiday_package_request.sections.flight')],
        ['id' => 4, 'label' => __('holiday_package_request.sections.hotel')],
        ['id' => 5, 'label' => 'Activities'],
        ['id' => 6, 'label' => __('holiday_package_request.sections.contact')],
    ];
@endphp
<div id="holidayPackageRequestWizard" class="hpr-wizard{{ $embedded ? ' hpr-wizard--embedded' : '' }}">
    <div class="hpr-wizard-header">
        <div class="d-flex align-items-start gap-3">
            <span class="hpr-wizard-icon" aria-hidden="true"><i class="far fa-paper-plane"></i></span>
            <div>
                <h5 class="hpr-wizard-title mb-0">{{ __('holiday_package_request.modal_title') }}</h5>
                <p class="hpr-wizard-intro mb-0">{{ __('holiday_package_request.modal_intro') }}</p>
            </div>
        </div>
    </div>

    <div class="hpr-wizard-progress" aria-live="polite">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="hpr-step-indicator" id="hpr-step-indicator">Step 1 of 6</span>
            <span class="hpr-step-name" id="hpr-step-name">{{ $wizardSteps[0]['label'] }}</span>
        </div>
        <div class="hpr-progress-track" role="progressbar" aria-valuemin="1" aria-valuemax="6" aria-valuenow="1">
            <div class="hpr-progress-bar" id="hpr-progress-bar" style="width: 16.666%;"></div>
        </div>
        <div class="hpr-progress-steps" role="list">
            @foreach($wizardSteps as $step)
                <div class="hpr-progress-step {{ $step['id'] === 1 ? 'is-active' : '' }}" data-step="{{ $step['id'] }}" role="listitem">
                    <span class="hpr-progress-step-dot" aria-hidden="true">{{ $step['id'] }}</span>
                    <span class="hpr-progress-step-label">{{ $step['label'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <form id="holidayPackageRequestForm" method="POST" action="{{ route('holiday-package-requests.store') }}" novalidate>
        @csrf
        <input type="hidden" name="locale" value="{{ $locale ?? app()->getLocale() }}">

        <div id="holidayPackageRequestAlert" class="alert d-none py-2 mb-2" role="alert"></div>

        <div class="hpr-wizard-body">
            <div class="hpr-wizard-step" data-step="1">
                <h6 class="hpr-wizard-step-title">{{ __('holiday_package_request.sections.travel') }}</h6>
                <div class="row g-2">
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-departure-airport">{{ __('holiday_package_request.fields.departure_airport') }}</label>
                        <input type="text" class="form-control form-control-sm" id="hpr-departure-airport" name="departure_airport" maxlength="255">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-destination">{{ __('holiday_package_request.fields.destination') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="hpr-destination" name="destination" required maxlength="255">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-earliest-departure">{{ __('holiday_package_request.fields.earliest_departure_date') }}</label>
                        <input type="text" class="form-control form-control-sm date-picker" id="hpr-earliest-departure" name="earliest_departure_date" autocomplete="off">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-latest-return">{{ __('holiday_package_request.fields.latest_return_date') }}</label>
                        <input type="text" class="form-control form-control-sm date-picker" id="hpr-latest-return" name="latest_return_date" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="hpr-wizard-step d-none" data-step="2">
                <h6 class="hpr-wizard-step-title">Traveller Information</h6>
                <div class="row g-2 align-items-end">
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-adults">{{ __('holiday_package_request.fields.adults') }}</label>
                        <input type="number" class="form-control form-control-sm" id="hpr-adults" name="adults" min="1" max="20" value="2" required>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-children">{{ __('holiday_package_request.fields.children') }}</label>
                        <input type="number" class="form-control form-control-sm" id="hpr-children" name="children" min="0" max="10" value="0">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-duration">{{ __('holiday_package_request.fields.duration') }}</label>
                        <input type="text" class="form-control form-control-sm" id="hpr-duration" name="duration" maxlength="120" placeholder="e.g. 7 nights">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-budget-amount">{{ __('holiday_package_request.fields.budget_amount') }}</label>
                        <div class="input-group input-group-sm">
                            <input type="number" class="form-control" id="hpr-budget-amount" name="budget_amount" min="0" step="0.01" placeholder="0">
                            <select class="form-select" id="hpr-budget-currency" name="budget_currency" style="max-width: 88px;">
                                <option value="">{{ __('holiday_package_request.not_specified') }}</option>
                                @foreach($config['budget_currencies'] as $currency)
                                    <option value="{{ $currency }}" @selected($currency === 'CHF')>{{ $currency }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div id="hpr-child-ages" class="hpr-child-ages-row d-none"></div>
                <div class="row g-2 mt-2">
                    <div class="col-md-8 col-lg-6">
                        <label class="form-label" for="hpr-holiday-type-toggle">Holiday Type</label>
                        @include('partials.holiday-package-request._multiselect', [
                            'name' => 'holiday_types',
                            'id' => 'hpr-holiday-type',
                            'options' => $config['holiday_types'] ?? [],
                            'labels' => $optionLabels['holiday_types'] ?? [],
                            'placeholder' => 'Select holiday types',
                        ])
                    </div>
                    <div class="col-md-4 col-lg-3">
                        <label class="form-label" for="hpr-priority">Priority</label>
                        @if($config['has_active_priorities'] ?? false)
                            <select class="form-select form-select-sm" id="hpr-priority" name="priority" data-hpr-ui-field="priority">
                                @foreach($config['priorities'] ?? [] as $slug)
                                    <option value="{{ $slug }}" @selected($loop->first)>{{ $optionLabels['priorities'][$slug] ?? $slug }}</option>
                                @endforeach
                            </select>
                        @else
                            <select class="form-select form-select-sm" id="hpr-priority" disabled aria-disabled="true">
                                <option>No active request priorities configured.</option>
                            </select>
                        @endif
                    </div>
                </div>
            </div>

            <div class="hpr-wizard-step d-none" data-step="3">
                <h6 class="hpr-wizard-step-title">{{ __('holiday_package_request.sections.flight') }}</h6>
                <div class="row g-2">
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-travel-class">{{ __('holiday_package_request.fields.travel_class') }}</label>
                        <select class="form-select form-select-sm" id="hpr-travel-class" name="travel_class">
                            <option value="">{{ __('holiday_package_request.not_specified') }}</option>
                            @foreach($config['travel_classes'] as $key)
                                <option value="{{ $key }}">{{ $optionLabels['travel_classes'][$key] ?? $key }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-airline">{{ __('holiday_package_request.fields.preferred_airline') }}</label>
                        @if($config['has_active_airlines'] ?? false)
                            <select class="form-select form-select-sm" id="hpr-airline" name="preferred_airline">
                                <option value="">{{ __('holiday_package_request.not_specified') }}</option>
                                @foreach($config['airline_options'] ?? [] as $airline)
                                    <option value="{{ $airline['slug'] }}">
                                        {{ $airline['name'] }}{{ $airline['code'] ? ' ('.$airline['code'].')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <select class="form-select form-select-sm" id="hpr-airline" disabled aria-disabled="true">
                                <option>No airlines configured.</option>
                            </select>
                        @endif
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-outbound-time">{{ __('holiday_package_request.fields.outbound_time_preference') }}</label>
                        <select class="form-select form-select-sm" id="hpr-outbound-time" name="outbound_time_preference">
                            <option value="">{{ __('holiday_package_request.not_specified') }}</option>
                            @foreach($config['time_preferences'] as $key)
                                <option value="{{ $key }}">{{ $optionLabels['time_preferences'][$key] ?? $key }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-return-time">{{ __('holiday_package_request.fields.return_time_preference') }}</label>
                        <select class="form-select form-select-sm" id="hpr-return-time" name="return_time_preference">
                            <option value="">{{ __('holiday_package_request.not_specified') }}</option>
                            @foreach($config['time_preferences'] as $key)
                                <option value="{{ $key }}">{{ $optionLabels['time_preferences'][$key] ?? $key }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <p class="hpr-wizard-subsection-title mb-1">{{ __('holiday_package_request.sections.flight') }} Options</p>
                        <div class="hpr-inline-checks">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="direct_flight_only" value="1" id="hpr-direct-flight">
                                <label class="form-check-label" for="hpr-direct-flight">{{ __('holiday_package_request.fields.direct_flight_only') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="transfer_allowed" value="1" id="hpr-transfer">
                                <label class="form-check-label" for="hpr-transfer">{{ __('holiday_package_request.fields.transfer_allowed') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="rail_and_fly" value="1" id="hpr-rail-fly">
                                <label class="form-check-label" for="hpr-rail-fly">{{ __('holiday_package_request.fields.rail_and_fly') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hpr-wizard-step d-none" data-step="4">
                <h6 class="hpr-wizard-step-title">{{ __('holiday_package_request.sections.hotel') }} &amp; {{ __('holiday_package_request.sections.accommodation') }}</h6>

                <p class="hpr-wizard-subsection-title">{{ __('holiday_package_request.sections.accommodation') }}</p>
                <div class="row g-2 mb-3">
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label" for="hpr-room-types-toggle">{{ __('holiday_package_request.fields.room_type') }}</label>
                        @include('partials.holiday-package-request._multiselect', [
                            'name' => 'room_types',
                            'id' => 'hpr-room-types',
                            'options' => $config['room_types'],
                            'labels' => $optionLabels['room_types'] ?? [],
                        ])
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label" for="hpr-board-types-toggle">{{ __('holiday_package_request.fields.board_type') }}</label>
                        @include('partials.holiday-package-request._multiselect', [
                            'name' => 'board_types',
                            'id' => 'hpr-board-types',
                            'options' => $config['board_types'],
                            'labels' => $optionLabels['board_types'] ?? [],
                        ])
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label" for="hpr-room-amenities-toggle">{{ __('holiday_package_request.fields.room_amenities') }}</label>
                        @include('partials.holiday-package-request._multiselect', [
                            'name' => 'room_amenities',
                            'id' => 'hpr-room-amenities',
                            'options' => $config['room_amenities'],
                            'labels' => $optionLabels['room_amenities'] ?? [],
                        ])
                    </div>
                </div>

                <p class="hpr-wizard-subsection-title">{{ __('holiday_package_request.sections.hotel') }}</p>
                <div class="row g-2 mb-3">
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-hotel-category">{{ __('holiday_package_request.fields.hotel_category') }}</label>
                        <select class="form-select form-select-sm" id="hpr-hotel-category" name="hotel_category">
                            <option value="">{{ __('holiday_package_request.not_specified') }}</option>
                            @foreach($config['hotel_categories'] as $key)
                                <option value="{{ $key }}">{{ $optionLabels['hotel_categories'][$key] ?? $key }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-hotel-recommendation">{{ __('holiday_package_request.fields.hotel_recommendation') }}</label>
                        <select class="form-select form-select-sm" id="hpr-hotel-recommendation" name="hotel_recommendation">
                            <option value="">{{ __('holiday_package_request.not_specified') }}</option>
                            @foreach($config['hotel_recommendations'] as $value)
                                <option value="{{ $value }}">{{ $value }}%</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-sea-view">{{ __('holiday_package_request.fields.sea_view') }}</label>
                        <select class="form-select form-select-sm" id="hpr-sea-view" name="sea_view">
                            <option value="">{{ __('holiday_package_request.not_specified') }}</option>
                            @foreach($config['sea_views'] as $key)
                                <option value="{{ $key }}">{{ $optionLabels['sea_views'][$key] ?? $key }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-hotel-features-toggle">{{ __('holiday_package_request.fields.hotel_features') }}</label>
                        @include('partials.holiday-package-request._multiselect', [
                            'name' => 'hotel_features',
                            'id' => 'hpr-hotel-features',
                            'options' => $config['hotel_features'],
                            'labels' => $optionLabels['hotel_features'] ?? [],
                        ])
                    </div>
                </div>

                <p class="hpr-wizard-subsection-title">{{ __('holiday_package_request.sections.family') }}</p>
                <div class="hpr-inline-checks">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="kids_club" value="1" id="hpr-kids-club">
                        <label class="form-check-label" for="hpr-kids-club">{{ __('holiday_package_request.fields.kids_club') }}</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="babysitting" value="1" id="hpr-babysitting">
                        <label class="form-check-label" for="hpr-babysitting">{{ __('holiday_package_request.fields.babysitting') }}</label>
                    </div>
                </div>
            </div>

            <div class="hpr-wizard-step d-none" data-step="5">
                <h6 class="hpr-wizard-step-title">{{ __('holiday_package_request.sections.sports') }}, {{ __('holiday_package_request.sections.beach') }} &amp; {{ __('holiday_package_request.sections.wellness') }}</h6>
                <div class="row g-2">
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label" for="hpr-sports-toggle">{{ __('holiday_package_request.sections.sports') }}</label>
                        @include('partials.holiday-package-request._multiselect', [
                            'name' => 'sports',
                            'id' => 'hpr-sports',
                            'options' => $config['sports'],
                            'labels' => $optionLabels['sports'] ?? [],
                        ])
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label" for="hpr-beach-toggle">{{ __('holiday_package_request.sections.beach') }}</label>
                        @include('partials.holiday-package-request._multiselect', [
                            'name' => 'beach_preferences',
                            'id' => 'hpr-beach',
                            'options' => $config['beach_preferences'],
                            'labels' => $optionLabels['beach_preferences'] ?? [],
                        ])
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <label class="form-label" for="hpr-wellness-toggle">{{ __('holiday_package_request.sections.wellness') }}</label>
                        @include('partials.holiday-package-request._multiselect', [
                            'name' => 'wellness',
                            'id' => 'hpr-wellness',
                            'options' => $config['wellness'],
                            'labels' => $optionLabels['wellness'] ?? [],
                        ])
                    </div>
                    <div class="col-12">
                        <p class="hpr-wizard-subsection-title mb-1">{{ __('holiday_package_request.sections.notes') }}</p>
                        <label class="form-label visually-hidden" for="hpr-additional-notes">{{ __('holiday_package_request.fields.additional_notes') }}</label>
                        <textarea class="form-control form-control-sm" id="hpr-additional-notes" name="additional_notes" rows="3" maxlength="5000" placeholder="{{ __('holiday_package_request.fields.additional_notes') }}"></textarea>
                    </div>
                </div>
            </div>

            <div class="hpr-wizard-step d-none" data-step="6">
                <h6 class="hpr-wizard-step-title">{{ __('holiday_package_request.sections.contact') }}</h6>
                <div class="row g-2">
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-full-name">{{ __('holiday_package_request.fields.full_name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="hpr-full-name" name="full_name" required maxlength="255">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-email">{{ __('holiday_package_request.fields.email') }} <span class="text-danger">*</span></label>
                        <input type="email" class="form-control form-control-sm" id="hpr-email" name="email" required maxlength="255">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-phone">{{ __('holiday_package_request.fields.phone') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" id="hpr-phone" name="phone" required maxlength="50">
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label" for="hpr-country">{{ __('holiday_package_request.fields.country') }}</label>
                        <input type="text" class="form-control form-control-sm" id="hpr-country" name="country" maxlength="120">
                    </div>
                    <div class="col-12">
                        <label class="form-label d-block mb-1">Preferred Contact Method</label>
                        @if($config['has_active_contact_methods'] ?? false)
                            <div class="hpr-contact-methods" data-hpr-ui-field="preferred_contact_method">
                                @foreach($config['contact_methods'] ?? [] as $slug)
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="preferred_contact_method" id="hpr-contact-{{ $slug }}" value="{{ $slug }}" @checked($loop->first)>
                                        <label class="form-check-label" for="hpr-contact-{{ $slug }}">{{ $optionLabels['contact_methods'][$slug] ?? $slug }}</label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="small text-muted mb-0">No active contact methods configured.</p>
                        @endif
                    </div>
                </div>
                <div class="hpr-gdpr">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="hpr-gdpr-consent" name="gdpr_consent" value="1" required>
                        <label class="form-check-label" for="hpr-gdpr-consent">
                            I agree that L'Heure de Voyage may contact me regarding my holiday request.
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="hpr-wizard-footer">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="hpr-back-btn" disabled>
                <span class="far fa-arrow-left me-1"></span>Back
            </button>
            <div class="hpr-wizard-footer-actions">
                @unless($embedded)
                    <button type="button" class="btn btn-secondary btn-sm hpr-wizard-collapse">{{ __('holiday_package_request.close') }}</button>
                @endunless
                <button type="button" class="btn btn-primary btn-sm" id="hpr-next-btn">
                    Next<span class="far fa-arrow-right ms-1"></span>
                </button>
                <button type="button" class="theme-btn btn-sm d-none" id="hpr-submit-btn">
                    <span class="far fa-paper-plane me-1"></span>{{ __('holiday_package_request.submit') }}
                </button>
            </div>
        </div>
    </form>
</div>
