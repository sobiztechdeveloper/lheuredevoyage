<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InsuranceBookingRequest;
use App\Services\ActivityLogService;
use App\Services\InsuranceBookingNotificationService;
use App\Services\InsuranceBookingRequestService;
use App\Services\InsuranceDocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InsuranceBookingRequestAdminController extends Controller
{
    public function __construct(
        protected InsuranceBookingRequestService $insuranceBookingService,
        protected InsuranceBookingNotificationService $notifications,
        protected InsuranceDocumentService $documentService,
        protected ActivityLogService $activityLog,
    ) {}

    public function index(Request $request): View
    {
        $requests = InsuranceBookingRequest::query()
            ->with('travelInsurance')
            ->withCount('travelers')
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->when($request->input('q'), function ($q, $term) {
                $q->where(function ($inner) use ($term) {
                    $inner->where('reference_number', 'like', "%{$term}%")
                        ->orWhere('contact_email', 'like', "%{$term}%")
                        ->orWhere('contact_phone', 'like', "%{$term}%")
                        ->orWhere('destination', 'like', "%{$term}%")
                        ->orWhereHas('travelInsurance', fn ($c) => $c->where('title', 'like', "%{$term}%")->orWhere('name', 'like', "%{$term}%"));
                });
            })
            ->when($request->input('destination'), fn ($q, $dest) => $q->where(function ($inner) use ($dest) {
                $inner->where('destination_country', 'like', "%{$dest}%")
                    ->orWhere('destination', 'like', "%{$dest}%");
            }))
            ->when($request->input('company'), function ($q, $company) {
                $q->whereHas('travelInsurance', fn ($c) => $c->where('insurance_company', 'like', "%{$company}%"));
            })
            ->when($request->input('travel_from'), fn ($q, $from) => $q->whereDate('travel_start', '>=', $from))
            ->when($request->input('travel_to'), fn ($q, $to) => $q->whereDate('travel_start', '<=', $to))
            ->when($request->input('submitted_from'), fn ($q, $from) => $q->whereDate('created_at', '>=', $from))
            ->when($request->input('submitted_to'), fn ($q, $to) => $q->whereDate('created_at', '<=', $to))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.insurance-booking-requests.index', [
            'requests' => $requests,
            'statuses' => InsuranceBookingRequest::STATUSES,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
            'filterDestination' => $request->input('destination'),
            'filterCompany' => $request->input('company'),
            'travelFrom' => $request->input('travel_from'),
            'travelTo' => $request->input('travel_to'),
            'submittedFrom' => $request->input('submitted_from'),
            'submittedTo' => $request->input('submitted_to'),
        ]);
    }

    public function show(InsuranceBookingRequest $insuranceBookingRequest): View
    {
        $insuranceBookingRequest->load([
            'travelers',
            'travelInsurance.benefits',
            'travelInsurance.exclusions',
            'customer',
            'documents',
            'statusHistories' => fn ($q) => $q->latest(),
            'statusHistories.changedBy',
            'quotes',
        ]);

        return view('admin.insurance-booking-requests.show', [
            'bookingRequest' => $insuranceBookingRequest,
            'statuses' => InsuranceBookingRequest::STATUSES,
            'adminDocTypes' => config('insurance.admin_document_types', []),
        ]);
    }

    public function update(Request $request, InsuranceBookingRequest $insuranceBookingRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:'.implode(',', InsuranceBookingRequest::STATUSES)],
            'agent_notes' => ['nullable', 'string', 'max:5000'],
            'status_note' => ['nullable', 'string', 'max:1000'],
            'policy' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'invoice' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
            'coverage_document' => ['nullable', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $previousStatus = $insuranceBookingRequest->status;
        $newStatus = $data['status'];
        unset($data['status'], $data['status_note']);

        $adminUploads = [
            'policy' => ['column' => 'policy_path', 'type' => 'policy'],
            'invoice' => ['column' => 'invoice_path', 'type' => 'invoice'],
            'coverage_document' => ['column' => 'coverage_document_path', 'type' => 'coverage_certificate'],
            'claim_instructions' => ['column' => 'claim_instructions_path', 'type' => 'claim_instructions'],
        ];
        foreach ($adminUploads as $input => $meta) {
            if ($request->hasFile($input)) {
                $data[$meta['column']] = $this->documentService->storeAdminPdf(
                    $insuranceBookingRequest,
                    $request->file($input),
                    $meta['type'],
                );
            }
        }

        unset($data['policy'], $data['invoice'], $data['coverage_document'], $data['claim_instructions']);
        $data['updated_by'] = auth()->id();
        $insuranceBookingRequest->update($data);

        if ($previousStatus !== $newStatus) {
            $this->insuranceBookingService->updateStatus(
                $insuranceBookingRequest,
                $newStatus,
                $request->input('status_note'),
            );
            $this->notifications->notifyStatusChanged($insuranceBookingRequest->fresh(), $previousStatus);
        }

        if ($request->hasFile('policy')) {
            $this->notifications->notifyDocumentUploaded($insuranceBookingRequest->fresh(), 'Insurance Policy');
        }
        if ($request->hasFile('invoice')) {
            $this->notifications->notifyDocumentUploaded($insuranceBookingRequest->fresh(), 'Invoice');
        }
        if ($request->hasFile('coverage_document')) {
            $this->notifications->notifyDocumentUploaded($insuranceBookingRequest->fresh(), 'Coverage Document');
        }

        $this->activityLog->log('insurance_booking_request.updated', $insuranceBookingRequest, [
            'reference' => $insuranceBookingRequest->reference_number,
        ]);

        return back()->with('success', 'Insurance booking request updated.');
    }
}
