<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\StreamsBookingRequestFiles;
use App\Models\CarBookingDriver;
use App\Models\CarBookingRequest;
use App\Models\CruiseBookingPassenger;
use App\Models\CruiseBookingRequest;
use App\Models\CruiseRequestDocument;
use App\Models\FlightBookingPassenger;
use App\Models\FlightBookingRequest;
use App\Models\HotelBookingRequest;
use App\Models\InsuranceBookingRequest;
use App\Models\InsuranceRequestDocument;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingRequestFileController extends Controller
{
    use StreamsBookingRequestFiles;
    public function flightDocument(Request $request, FlightBookingRequest $flightBookingRequest, string $document): StreamedResponse
    {
        abort_unless($flightBookingRequest->user_id === $request->user()->id, 403);

        $path = match ($document) {
            'ticket' => $flightBookingRequest->ticket_path,
            'invoice' => $flightBookingRequest->invoice_path,
            default => null,
        };

        return $this->streamBookingRequestFile($path);
    }

    public function flightPassport(
        Request $request,
        FlightBookingRequest $flightBookingRequest,
        FlightBookingPassenger $passenger,
    ): StreamedResponse {
        abort_unless($flightBookingRequest->user_id === $request->user()->id, 403);
        abort_unless($passenger->flight_booking_request_id === $flightBookingRequest->id, 404);

        return $this->streamBookingRequestFile($passenger->passport_file);
    }

    public function hotelDocument(Request $request, HotelBookingRequest $hotelBookingRequest, string $document): StreamedResponse
    {
        abort_unless($hotelBookingRequest->customer_id === $request->user()->id, 403);

        $path = match ($document) {
            'voucher' => $hotelBookingRequest->voucher_path,
            'invoice' => $hotelBookingRequest->invoice_path,
            default => null,
        };

        return $this->streamBookingRequestFile($path);
    }

    public function cruiseDocument(Request $request, CruiseBookingRequest $cruiseBookingRequest, string $document): StreamedResponse
    {
        abort_unless($cruiseBookingRequest->customer_id === $request->user()->id, 403);

        $path = match ($document) {
            'voucher' => $cruiseBookingRequest->voucher_path,
            'invoice' => $cruiseBookingRequest->invoice_path,
            'ticket' => $cruiseBookingRequest->ticket_path,
            'boarding', 'boarding_instructions' => $cruiseBookingRequest->boarding_instructions_path,
            'excursion', 'excursion_details' => $cruiseBookingRequest->excursion_details_path,
            default => null,
        };

        if ($path) {
            return $this->streamBookingRequestFile($path);
        }

        abort(404);
    }

    public function cruiseUploadedDocument(
        Request $request,
        CruiseBookingRequest $cruiseBookingRequest,
        CruiseRequestDocument $document,
    ): StreamedResponse {
        abort_unless($cruiseBookingRequest->customer_id === $request->user()->id, 403);
        abort_unless($document->cruise_booking_request_id === $cruiseBookingRequest->id, 404);

        return $this->streamBookingRequestFile($document->path, $document->disk);
    }

    public function cruisePassport(
        Request $request,
        CruiseBookingRequest $cruiseBookingRequest,
        CruiseBookingPassenger $passenger,
    ): StreamedResponse {
        abort_unless($cruiseBookingRequest->customer_id === $request->user()->id, 403);
        abort_unless($passenger->cruise_booking_request_id === $cruiseBookingRequest->id, 404);

        return $this->streamBookingRequestFile($passenger->passport_file);
    }

    public function carDocument(Request $request, CarBookingRequest $carBookingRequest, string $document): StreamedResponse
    {
        abort_unless($carBookingRequest->customer_id === $request->user()->id, 403);

        $path = match ($document) {
            'voucher' => $carBookingRequest->voucher_path,
            'invoice' => $carBookingRequest->invoice_path,
            'rental_agreement' => $carBookingRequest->rental_agreement_path,
            default => null,
        };

        return $this->streamBookingRequestFile($path);
    }

    public function carDriverFile(
        Request $request,
        CarBookingRequest $carBookingRequest,
        CarBookingDriver $driver,
        string $file,
    ): StreamedResponse {
        abort_unless($carBookingRequest->customer_id === $request->user()->id, 403);
        abort_unless($driver->car_booking_request_id === $carBookingRequest->id, 404);

        $path = match ($file) {
            'passport' => $driver->passport_file,
            'license' => $driver->license_file,
            default => null,
        };

        return $this->streamBookingRequestFile($path);
    }

    public function insuranceDocument(Request $request, InsuranceBookingRequest $insuranceBookingRequest, string $document): StreamedResponse
    {
        abort_unless($insuranceBookingRequest->customer_id === $request->user()->id, 403);

        $path = match ($document) {
            'policy' => $insuranceBookingRequest->policy_path,
            'invoice' => $insuranceBookingRequest->invoice_path,
            'coverage', 'coverage_certificate' => $insuranceBookingRequest->coverage_document_path,
            'claim_instructions' => $insuranceBookingRequest->claim_instructions_path,
            default => null,
        };

        if ($path) {
            return $this->streamBookingRequestFile($path);
        }

        abort(404);
    }

    public function insuranceUploadedDocument(
        Request $request,
        InsuranceBookingRequest $insuranceBookingRequest,
        InsuranceRequestDocument $document,
    ): StreamedResponse {
        abort_unless($insuranceBookingRequest->customer_id === $request->user()->id, 403);
        abort_unless($document->insurance_booking_request_id === $insuranceBookingRequest->id, 404);

        return $this->streamBookingRequestFile($document->path, $document->disk);
    }

}
