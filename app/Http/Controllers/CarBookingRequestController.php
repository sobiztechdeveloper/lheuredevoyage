<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\AuthorizesBookingConfirmation;
use App\Http\Requests\StoreCarBookingRequest;
use App\Models\CarBookingRequest;
use App\Models\RentalCar;
use App\Services\ActivityLogService;
use App\Services\CarBookingNotificationService;
use App\Services\CarBookingRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CarBookingRequestController extends Controller
{
    use AuthorizesBookingConfirmation;

    public function __construct(
        protected CarBookingRequestService $carBookingService,
        protected CarBookingNotificationService $notifications,
        protected ActivityLogService $activityLog,
    ) {}

    public function create(Request $request, RentalCar $rentalCar): View
    {
        if (! RentalCar::query()->active()->whereKey($rentalCar->id)->exists()) {
            abort(404);
        }

        $context = $this->carBookingService->buildContext($rentalCar, $request->query());

        return view('pages.publicView.rentalCar.carBookingWizard', [
            'rentalCar' => $rentalCar,
            'context' => $context,
            'searchQuery' => $request->query(),
        ]);
    }

    public function store(StoreCarBookingRequest $request): RedirectResponse
    {
        $rentalCar = RentalCar::query()->active()->findOrFail($request->integer('rental_car_id'));
        $data = $request->validated();
        $data['drivers'] = $this->storeDriverFiles($request, $data['drivers'] ?? []);

        $booking = $this->carBookingService->create($rentalCar, $data);

        $this->notifications->notifyCreated($booking);
        $this->activityLog->log('car_booking_request.created', $booking, [
            'reference' => $booking->reference_number,
        ]);

        return $this->redirectToSignedBookingConfirmation('rentalcar.booking.confirmation', $booking);
    }

    public function confirmation(Request $request, CarBookingRequest $carBookingRequest): View
    {
        $this->authorizeBookingConfirmation($request, $carBookingRequest);

        $carBookingRequest->load(['drivers', 'rentalCar']);

        return view('pages.publicView.rentalCar.carBookingConfirmation', [
            'booking' => $carBookingRequest,
        ]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $drivers
     * @return array<int, array<string, mixed>>
     */
    protected function storeDriverFiles(Request $request, array $drivers): array
    {
        foreach ($drivers as $index => &$driver) {
            $license = $request->file("drivers.{$index}.license_file");
            $passport = $request->file("drivers.{$index}.passport_file");

            if ($license) {
                $driver['license_file'] = $license->store('car-bookings/licenses', 'local');
            }
            if ($passport) {
                $driver['passport_file'] = $passport->store('car-bookings/passports', 'local');
            }
        }

        return $drivers;
    }
}
