<?php

namespace App\Services;

use App\Models\InsuranceBookingRequest;
use App\Models\InsuranceBookingRequestStatusHistory;
use App\Models\InsuranceBookingTraveler;
use App\Models\Quote;
use App\Models\TravelInsurance;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class InsuranceBookingRequestService
{
    public function __construct(
        protected InsuranceDocumentService $documents,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function buildQuoteContext(?TravelInsurance $preselected = null, array $searchParams = []): array
    {
        $plans = TravelInsurance::query()->active()->ordered()->with(['benefits'])->get();

        return [
            'preselected_plan' => $preselected,
            'plans' => $plans,
            'travel_purposes' => config('insurance.travel_purposes', []),
            'default_currency' => 'CHF',
        ];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function createQuoteRequest(array $data): InsuranceBookingRequest
    {
        return DB::transaction(function () use ($data) {
            $plan = TravelInsurance::query()->active()->findOrFail($data['travel_insurance_id']);
            $travelers = is_array($data['travelers'] ?? null) ? $data['travelers'] : [];
            $travelerCount = max(1, count($travelers));
            $estimated = $data['estimated_amount'] ?? round((float) ($plan->base_premium ?? $plan->price) * $travelerCount, 2);

            $booking = InsuranceBookingRequest::query()->create([
                'reference_number' => InsuranceBookingRequest::generateReference(),
                'customer_id' => auth()->id(),
                'travel_insurance_id' => $plan->id,
                'status' => 'new',
                'destination' => $data['destination_country'] ?? $data['destination'] ?? null,
                'destination_country' => $data['destination_country'] ?? null,
                'purpose_of_travel' => $data['purpose_of_travel'] ?? null,
                'travel_start' => $data['travel_start'],
                'travel_end' => $data['travel_end'],
                'coverage_type' => $plan->plan_type_label(),
                'pre_existing_conditions' => (bool) ($data['pre_existing_conditions'] ?? false),
                'pregnancy' => (bool) ($data['pregnancy'] ?? false),
                'adventure_sports' => (bool) ($data['adventure_sports'] ?? false),
                'winter_sports' => (bool) ($data['winter_sports'] ?? false),
                'long_stay' => (bool) ($data['long_stay'] ?? false),
                'medical_notes' => $data['medical_notes'] ?? null,
                'additional_notes' => $data['additional_notes'] ?? null,
                'contact_email' => $data['contact_email'],
                'contact_phone' => $data['contact_phone'],
                'contact_whatsapp' => $data['contact_whatsapp'] ?? null,
                'address' => $data['address'] ?? null,
                'country' => $data['country'] ?? null,
                'city' => $data['city'] ?? null,
                'selected_policy' => [
                    'id' => $plan->id,
                    'company' => $plan->displayCompany(),
                    'name' => $plan->displayPlanName(),
                    'plan_code' => $plan->plan_code,
                    'plan_type' => $plan->plan_type,
                    'medical_coverage' => $plan->formattedMedicalCoverage(),
                    'premium' => $plan->displayPremium(),
                ],
                'estimated_amount' => $estimated,
                'currency' => $data['currency'] ?? $plan->premium_currency ?? 'CHF',
                'privacy_accepted' => (bool) ($data['privacy_accepted'] ?? false),
                'terms_accepted_at' => ! empty($data['accept_conditions']) ? now() : null,
                'created_by' => auth()->id(),
            ]);

            foreach ($travelers as $index => $traveler) {
                if ($index > 0 && empty(trim($traveler['first_name'] ?? ''))) {
                    continue;
                }
                $isPrimary = $index === 0 || ! empty($traveler['is_primary']);
                $record = InsuranceBookingTraveler::query()->create([
                    'insurance_booking_request_id' => $booking->id,
                    'is_primary' => $isPrimary,
                    'title' => $traveler['title'] ?? null,
                    'first_name' => $traveler['first_name'] ?? '',
                    'last_name' => $traveler['last_name'] ?? '',
                    'date_of_birth' => $traveler['date_of_birth'] ?? null,
                    'nationality' => $traveler['nationality'] ?? null,
                    'passport_number' => $traveler['passport_number'] ?? null,
                    'passport_expiry' => $traveler['passport_expiry'] ?? null,
                    'relationship' => $traveler['relationship'] ?? ($isPrimary ? 'self' : null),
                ]);

                if (! empty($traveler['passport_file']) && $traveler['passport_file'] instanceof UploadedFile) {
                    $this->documents->storeCustomerUpload($booking, $traveler['passport_file'], 'passport', $record->id);
                }
            }

            InsuranceBookingRequestStatusHistory::query()->create([
                'insurance_booking_request_id' => $booking->id,
                'old_status' => null,
                'new_status' => 'new',
                'notes' => 'Insurance quote request submitted.',
                'changed_by' => auth()->id(),
            ]);

            if (! empty($data['customer_documents']) && is_array($data['customer_documents'])) {
                foreach ($data['customer_documents'] as $type => $file) {
                    if ($file instanceof UploadedFile) {
                        $this->documents->storeCustomerUpload($booking, $file, (string) $type);
                    }
                }
            }

            return $booking->load(['travelers', 'travelInsurance.benefits', 'travelInsurance.exclusions', 'documents']);
        });
    }

    public function updateStatus(InsuranceBookingRequest $booking, string $status, ?string $notes = null): void
    {
        $previous = $booking->status;
        $booking->update([
            'status' => $status,
            'updated_by' => auth()->id(),
        ]);

        if ($previous !== $status) {
            InsuranceBookingRequestStatusHistory::query()->create([
                'insurance_booking_request_id' => $booking->id,
                'old_status' => $previous,
                'new_status' => $status,
                'notes' => $notes ?? 'Status updated.',
                'changed_by' => auth()->id(),
            ]);
        }
    }

    public function syncFromQuoteAcceptance(Quote $quote): void
    {
        if (! $quote->insurance_booking_request_id) {
            return;
        }

        $request = $quote->insuranceBookingRequest;
        if ($request && ! in_array($request->status, ['completed', 'cancelled', 'policy_issued'], true)) {
            $this->updateStatus($request, 'accepted', 'Customer accepted quote '.$quote->quote_number.'.');
        }
    }

    public function syncFromQuoteRejection(Quote $quote): void
    {
        if (! $quote->insurance_booking_request_id) {
            return;
        }

        $request = $quote->insuranceBookingRequest;
        if ($request && ! in_array($request->status, ['completed', 'cancelled', 'policy_issued'], true)) {
            $this->updateStatus($request, 'rejected', 'Customer rejected quote '.$quote->quote_number.'.');
        }
    }

    private function parseDate(mixed $value): ?Carbon
    {
        return parse_user_date($value);
    }
}
