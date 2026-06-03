@php
    $collapseId = 'flight-booking-collapse'.$result->id;
    $fromCode = $result->destinationCode($result->from_destination);
    $toCode = $result->destinationCode($result->to_destination);
@endphp
<!-- flight booking item -->
<div class="col-lg-12">
    <div class="flight-booking-item">
        <div class="flight-booking-wrapper">
            <div class="flight-booking-info">
                <div class="flight-booking-content">
                    <div class="flight-booking-airline">
                        <div class="flight-airline-img{{ $result->catalogFlight?->image_url ? '' : ' flight-airline-initial' }}" aria-hidden="true">
                            @if($result->catalogFlight?->image_url)
                                <img src="{{ $result->catalogFlight->image_url }}" alt="{{ $result->airline }}">
                            @else
                                <span>{{ $result->airlineInitials() }}</span>
                            @endif
                        </div>
                        <h5 class="flight-airline-name">{{ $result->airline }}</h5>
                    </div>
                    <div class="flight-booking-time">
                        <div class="start-time">
                            <div class="start-time-icon">
                                <i class="fal fa-plane-departure"></i>
                            </div>
                            <div class="start-time-info">
                                <h6 class="start-time-text">{{ $result->departure_at->format('H:i') }}</h6>
                                <span class="flight-destination">{{ $fromCode }}</span>
                            </div>
                        </div>
                        <div class="flight-stop">
                            <span class="flight-stop-number">{{ $result->stopLabel() }}</span>
                            <div class="flight-stop-arrow{{ $result->stops > 0 ? ' flight-has-stop' : '' }}"></div>
                        </div>
                        <div class="end-time">
                            <div class="start-time-icon">
                                <i class="fal fa-plane-arrival"></i>
                            </div>
                            <div class="start-time-info">
                                <h6 class="end-time-text">{{ $result->arrival_at->format('H:i') }}</h6>
                                <span class="flight-destination">{{ $toCode }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flight-booking-duration">
                        <span class="duration-text">{{ $result->duration }}</span>
                    </div>
                </div>
            </div>
            <div class="flight-booking-price">
                <div class="price-info">
                    <span class="price-amount">${{ number_format($result->price, 0) }}</span>
                </div>
                <a href="{{ route('flight.booking.create', $result) }}" class="theme-btn">Book Now<i class="fas fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <div class="flight-booking-detail">
            <div class="flight-booking-detail-header">
                <p>{{ $result->refundableLabel() }}</p>
                <a href="#{{ $collapseId }}" data-bs-toggle="collapse" role="button"
                    aria-expanded="false" aria-controls="{{ $collapseId }}">Flight
                    Details <i class="far fa-angle-down"></i></a>
            </div>
            <div class="collapse" id="{{ $collapseId }}">
                <div class="flight-booking-detail-wrapper">
                    <div class="row">
                        <div class="col-lg-12 col-xl-6">
                            <div class="flight-booking-detail-left">
                                <ul class="nav nav-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" type="button" role="tab">{{ $fromCode }} - {{ $toCode }}</button>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" role="tabpanel">
                                        <div class="flight-booking-detail-info">
                                            <div class="flight-booking-airline">
                                                <div class="flight-airline-img{{ $result->catalogFlight?->image_url ? '' : ' flight-airline-initial' }}" aria-hidden="true">
                                                    @if($result->catalogFlight?->image_url)
                                                        <img src="{{ $result->catalogFlight->image_url }}" alt="{{ $result->airline }}">
                                                    @else
                                                        <span>{{ $result->airlineInitials() }}</span>
                                                    @endif
                                                </div>
                                                <div class="flight-airline-info flex-grow-1">
                                                    <h5 class="flight-airline-name">{{ $result->airline }}</h5>
                                                    <span class="flight-airline-model">{{ $result->flight_number }}</span>
                                                </div>
                                                <p class="flight-airline-class">( {{ $result->cabinClassLabel() }} )</p>
                                            </div>
                                            <div class="flight-booking-time">
                                                <div class="start-time">
                                                    <div class="start-time-icon">
                                                        <i class="fal fa-plane-departure"></i>
                                                    </div>
                                                    <div class="start-time-info">
                                                        <h6 class="start-time-text">{{ $result->departure_at->format('H:i') }}</h6>
                                                        <p class="flight-full-date">{{ $result->departure_at->format('D, d M, Y') }}</p>
                                                        <span class="flight-destination">{{ $result->from_destination }} ({{ $fromCode }})</span>
                                                    </div>
                                                </div>
                                                <div class="flight-stop">
                                                    <span class="flight-stop-number">{{ $result->stopLabel() }}</span>
                                                    <div class="flight-stop-arrow{{ $result->stops > 0 ? ' flight-has-stop' : '' }}"></div>
                                                    <div class="flight-booking-duration">
                                                        <span class="duration-text">{{ $result->duration }}</span>
                                                    </div>
                                                </div>
                                                <div class="end-time">
                                                    <div class="start-time-icon">
                                                        <i class="fal fa-plane-arrival"></i>
                                                    </div>
                                                    <div class="start-time-info">
                                                        <h6 class="end-time-text">{{ $result->arrival_at->format('H:i') }}</h6>
                                                        <p class="flight-full-date">{{ $result->arrival_at->format('D, d M, Y') }}</p>
                                                        <span class="flight-destination">{{ $result->to_destination }} ({{ $toCode }})</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-xl-6">
                            <div class="flight-booking-detail-right">
                                <div class="flight-booking-detail-info">
                                    <table class="table table-borderless">
                                        <tr>
                                            <th>Flight</th>
                                            <th>Cabin</th>
                                            <th>Baggage</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $result->flight_number }}</td>
                                            <td>{{ $result->cabinClassLabel() }}</td>
                                            <td>{{ $result->baggage_kg }} kg</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="flight-booking-detail-price">
                                    <h6 class="flight-booking-detail-price-title">Total</h6>
                                    <div class="flight-detail-price-amount">
                                        ${{ number_format($result->price, 0) }}
                                        @if($result->currency && $result->currency !== 'USD')
                                            <small class="d-block text-muted">{{ $result->currency }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
