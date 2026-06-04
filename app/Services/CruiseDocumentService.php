<?php

namespace App\Services;

use App\Models\CruiseBookingRequest;
use App\Models\CruiseRequestDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CruiseDocumentService
{
    public function storeCustomerUpload(
        CruiseBookingRequest $booking,
        UploadedFile $file,
        string $documentType,
    ): CruiseRequestDocument {
        $path = $file->store(
            'cruise-requests/'.$booking->id.'/'.Str::slug($documentType),
            'local'
        );

        return CruiseRequestDocument::query()->create([
            'cruise_booking_request_id' => $booking->id,
            'document_type' => $documentType,
            'disk' => 'local',
            'path' => $path,
            'original_name' => $file->getClientOriginalName(),
            'uploaded_by' => auth()->id(),
        ]);
    }

    public function storeAdminPdf(
        CruiseBookingRequest $booking,
        UploadedFile $file,
        string $documentType,
    ): string {
        $column = match ($documentType) {
            'voucher' => 'voucher_path',
            'invoice' => 'invoice_path',
            'ticket' => 'ticket_path',
            'boarding', 'boarding_instructions' => 'boarding_instructions_path',
            'excursion', 'excursion_details' => 'excursion_details_path',
            default => $documentType.'_path',
        };

        if ($booking->getAttribute($column)) {
            Storage::disk('local')->delete($booking->getAttribute($column));
        }

        return $file->store('cruise-requests/'.$booking->id.'/admin/'.Str::slug($documentType), 'local');
    }
}
