<?php

namespace App\Services;

use App\Models\InsuranceBookingRequest;
use App\Models\InsuranceRequestDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class InsuranceDocumentService
{
    public function storeCustomerUpload(
        InsuranceBookingRequest $booking,
        UploadedFile $file,
        string $documentType,
        ?int $travelerId = null,
    ): InsuranceRequestDocument {
        $path = $file->store(
            'insurance-requests/'.$booking->id.'/'.Str::slug($documentType),
            'local'
        );

        return InsuranceRequestDocument::query()->create([
            'insurance_booking_request_id' => $booking->id,
            'document_type' => $documentType.($travelerId ? '_'.$travelerId : ''),
            'disk' => 'local',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'uploaded_by' => auth()->id(),
        ]);
    }

    public function storeAdminPdf(
        InsuranceBookingRequest $booking,
        UploadedFile $file,
        string $documentType,
    ): string {
        if ($booking->getAttribute($this->legacyColumn($documentType))) {
            Storage::disk('local')->delete($booking->getAttribute($this->legacyColumn($documentType)));
        }

        return $file->store('insurance-requests/'.$booking->id.'/admin/'.Str::slug($documentType), 'local');
    }

    protected function legacyColumn(string $type): string
    {
        return match ($type) {
            'policy' => 'policy_path',
            'invoice' => 'invoice_path',
            'coverage_certificate', 'coverage' => 'coverage_document_path',
            'claim_instructions' => 'claim_instructions_path',
            default => $type.'_path',
        };
    }
}
