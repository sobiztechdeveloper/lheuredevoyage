<?php

namespace App\Services;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class BookingInvoiceService
{
    public function invoiceData(Booking $booking): array
    {
        $booking->load(['user', 'bookable']);

        return [
            'booking' => $booking,
            'company' => \App\Models\WebsiteSetting::cached(),
            'issuedAt' => now(),
        ];
    }

    public function pdfResponse(Booking $booking): Response
    {
        $pdf = Pdf::loadView('pdf.booking-invoice', $this->invoiceData($booking))
            ->setPaper('a4');

        return $pdf->download('invoice-'.$booking->reference.'.pdf');
    }
}
