<?php

namespace App\Services;

use App\Models\Cruise;
use App\Models\CruiseBookingPassenger;
use App\Models\CruiseBookingRequest;
use App\Models\CruiseBookingRequestStatusHistory;
use App\Models\CruiseCabin;
use App\Models\Quote;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class CruiseBookingRequestService
{
    public function __construct(
        protected CruiseDocumentService $documents,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function buildQuoteWizardContext(?Cruise $preselected = null, array $searchParams = []): array
    {
        $cruises = Cruise::query()->active()->ordered()->with(['itineraryDays', 'cabins', 'galleryImages'])->get();

        $cabinsByCruise = $cruises->mapWithKeys(function (Cruise $cruise) {
            return [
                $cruise->id => $cruise->cabins->map(fn (CruiseCabin $cab) => [
                    'id' => $cab->id,
                    'name' => $cab->name,
                    'type' => $cab->cabinTypeLabel(),
                    'description' => $cab->description,
                    'max_occupancy' => $cab->max_occupancy,
                    'max_adults' => $cab->max_adults,
                    'max_children' => $cab->max_children,
                    'price' => $cab->formattedPrice(),
                ])->values()->all(),
            ];
        })->all();

        return [
            'cruises' => $cruises,
            'cruise_cabins_by_cruise' => $cabinsByCruise,
            'preselected_cruise' => $preselected,
            'search_params' => $searchParams,
            'dining_preferences' => config('cruise.dining_preferences', []),
            'bed_preferences' => config('cruise.bed_preferences', []),
            'celebrations' => config('cruise.celebrations', []),
            'customer_document_types' => config('cruise.customer_document_types', []),
            'default_currency' => 'CHF',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function buildContext(Cruise $cruise, array $searchParams): array
    {
        $departureDate = $this->parseDate(
            $searchParams['departure_date'] ?? $searchParams['journey_date'] ?? $searchParams['journey-date'] ?? null,
        );
        $returnDate = $this->parseDate($searchParams['return_date'] ?? $searchParams['return-date'] ?? null);
        $adults = max(1, (int) ($searchParams['adults'] ?? $searchParams['adult'] ?? 1));
        $children = max(0, (int) ($searchParams['children'] ?? 0));
        $infants = max(0, (int) ($searchParams['infants'] ?? $searchParams['infant'] ?? 0));

        if (! $departureDate) {
            $departureDate = now()->addDays(30)->startOfDay();
        }

        $cruise->load(['cabins', 'itineraryDays']);

        return [
            'departure_date' => $departureDate,
            'return_date' => $returnDate,
            'adults' => $adults,
            'children' => $children,
            'infants' => $infants,
            'estimated_amount' => $this->estimateAmount($cruise, null, $adults, $children, $infants),
            'currency' => 'CHF',
            'cabins' => $cruise->cabins,
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createQuoteRequest(array $data): CruiseBookingRequest
    {
        return DB::transaction(function () use ($data) {
            $cruise = Cruise::query()->active()->with('cabins')->findOrFail($data['cruise_id']);
            $cabin = ! empty($data['cruise_cabin_id'])
                ? CruiseCabin::query()->where('cruise_id', $cruise->id)->findOrFail($data['cruise_cabin_id'])
                : null;

            $adults = max(1, (int) ($data['adults'] ?? 1));
            $children = max(0, (int) ($data['children'] ?? 0));
            $infants = max(0, (int) ($data['infants'] ?? 0));

            if ($cabin && ($adults + $children + $infants) > $cabin->max_occupancy) {
                throw new \InvalidArgumentException('Passenger count exceeds cabin occupancy.');
            }

            $booking = CruiseBookingRequest::query()->create([
                'reference_number' => CruiseBookingRequest::generateReference(),
                'customer_id' => auth()->id(),
                'cruise_id' => $cruise->id,
                'cruise_cabin_id' => $cabin?->id,
                'status' => 'new',
                'departure_date' => $data['departure_date'],
                'return_date' => $data['return_date'] ?? null,
                'cabin_type' => $cabin?->cabin_type ?? $data['cabin_type'] ?? null,
                'adults' => $adults,
                'children' => $children,
                'infants' => $infants,
                'contact_title' => $data['contact_title'] ?? null,
                'contact_name' => trim(($data['contact_first_name'] ?? '').' '.($data['contact_last_name'] ?? '')) ?: ($data['contact_name'] ?? ''),
                'contact_email' => $data['contact_email'],
                'contact_phone' => $data['contact_phone'],
                'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
                'country' => $data['country'] ?? null,
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'dining_preference' => $data['dining_preference'] ?? null,
                'bed_preference' => $data['bed_preference'] ?? null,
                'celebration' => $data['celebration'] ?? 'none',
                'dietary_requirements' => $data['dietary_requirements'] ?? null,
                'wheelchair_assistance' => (bool) ($data['wheelchair_assistance'] ?? false),
                'mobility_assistance' => (bool) ($data['mobility_assistance'] ?? false),
                'special_needs' => (bool) ($data['special_needs'] ?? false),
                'medical_conditions' => $data['medical_conditions'] ?? null,
                'additional_notes' => $data['additional_notes'] ?? null,
                'special_requests' => $data['special_requests'] ?? null,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_relationship' => $data['emergency_contact_relationship'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'emergency_contact_email' => $data['emergency_contact_email'] ?? null,
                'selected_cruise' => [
                    'id' => $cruise->id,
                    'name' => $cruise->displayName(),
                    'cruise_line' => $cruise->cruise_line,
                    'ship_name' => $cruise->ship_name,
                    'slug' => $cruise->slug,
                    'cabin' => $cabin?->name,
                ],
                'estimated_amount' => $data['estimated_amount'] ?? $this->estimateAmount($cruise, $cabin, $adults, $children, $infants),
                'currency' => $data['currency'] ?? 'CHF',
                'privacy_accepted' => true,
                'terms_accepted_at' => now(),
                'created_by' => auth()->id(),
            ]);

            $this->storePassengers($booking, $data);
            $this->storeUploadedDocuments($booking, $data['documents'] ?? []);

            CruiseBookingRequestStatusHistory::query()->create([
                'cruise_booking_request_id' => $booking->id,
                'old_status' => null,
                'new_status' => 'new',
                'notes' => 'Cruise quote request submitted.',
                'changed_by' => auth()->id(),
            ]);

            return $booking->load(['passengers', 'cruise', 'cabin', 'documents']);
        });
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(Cruise $cruise, array $data): CruiseBookingRequest
    {
        $data['cruise_id'] = $cruise->id;

        return $this->createQuoteRequest($data);
    }

    public function updateStatus(CruiseBookingRequest $booking, string $status, ?string $notes = null): void
    {
        $previous = $booking->status;
        $booking->update([
            'status' => $status,
            'updated_by' => auth()->id(),
        ]);

        if ($previous !== $status) {
            CruiseBookingRequestStatusHistory::query()->create([
                'cruise_booking_request_id' => $booking->id,
                'old_status' => $previous,
                'new_status' => $status,
                'notes' => $notes ?? 'Status updated.',
                'changed_by' => auth()->id(),
            ]);
        }
    }

    public function syncFromQuoteAcceptance(Quote $quote): void
    {
        if (! $quote->cruise_booking_request_id) {
            return;
        }

        $request = $quote->cruiseBookingRequest;
        if ($request && ! in_array($request->status, ['completed', 'cancelled', 'voucher_sent'], true)) {
            $this->updateStatus($request, 'accepted', 'Customer accepted quote '.$quote->quote_number.'.');
        }
    }

    public function syncFromQuoteRejection(Quote $quote): void
    {
        if (! $quote->cruise_booking_request_id) {
            return;
        }

        $request = $quote->cruiseBookingRequest;
        if ($request && ! in_array($request->status, ['completed', 'cancelled', 'voucher_sent'], true)) {
            $this->updateStatus($request, 'rejected', 'Customer rejected quote '.$quote->quote_number.'.');
        }
    }

    protected function estimateAmount(Cruise $cruise, ?CruiseCabin $cabin, int $adults, int $children, int $infants): float
    {
        $base = (float) ($cabin?->starting_price ?? $cruise->cabins->min('starting_price') ?? $cruise->price ?? 0);
        $pax = max(1, $adults + $children + $infants);

        return round($base * $pax, 2);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    protected function storePassengers(CruiseBookingRequest $booking, array $data): void
    {
        $lead = [
            'is_primary' => true,
            'title' => $data['contact_title'] ?? null,
            'first_name' => $data['contact_first_name'] ?? explode(' ', $data['contact_name'] ?? '')[0] ?? '',
            'last_name' => $data['contact_last_name'] ?? '',
            'passenger_type' => 'adult',
            'gender' => $data['contact_gender'] ?? null,
            'date_of_birth' => $data['contact_date_of_birth'] ?? null,
            'nationality' => $data['contact_nationality'] ?? null,
            'passport_number' => $data['contact_passport_number'] ?? null,
            'passport_expiry' => $data['contact_passport_expiry'] ?? null,
            'passport_country' => $data['country_of_residence'] ?? $data['country'] ?? null,
        ];
        CruiseBookingPassenger::query()->create(array_merge($lead, ['cruise_booking_request_id' => $booking->id]));

        $passengers = is_array($data['passengers'] ?? null) ? $data['passengers'] : [];
        foreach ($passengers as $passenger) {
            if (empty($passenger['first_name']) && empty($passenger['last_name'])) {
                continue;
            }
            CruiseBookingPassenger::query()->create([
                'cruise_booking_request_id' => $booking->id,
                'is_primary' => false,
                'title' => $passenger['title'] ?? null,
                'first_name' => $passenger['first_name'] ?? '',
                'last_name' => $passenger['last_name'] ?? '',
                'passenger_type' => $passenger['passenger_type'] ?? 'adult',
                'gender' => $passenger['gender'] ?? null,
                'date_of_birth' => $passenger['date_of_birth'] ?? null,
                'nationality' => $passenger['nationality'] ?? null,
                'passport_number' => $passenger['passport_number'] ?? null,
                'passport_expiry' => $passenger['passport_expiry'] ?? null,
                'passport_country' => $passenger['passport_country'] ?? null,
            ]);
        }
    }

    /**
     * @param  array<string, UploadedFile>  $documents
     */
    protected function storeUploadedDocuments(CruiseBookingRequest $booking, array $documents): void
    {
        foreach ($documents as $type => $file) {
            if ($file instanceof UploadedFile) {
                $this->documents->storeCustomerUpload($booking, $file, $type);
            }
        }
    }

    private function parseDate(mixed $value): ?Carbon
    {
        return parse_user_date($value);
    }
}
