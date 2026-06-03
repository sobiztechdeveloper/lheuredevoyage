<?php

namespace App\Services;

use App\Models\ContactDetail;
use App\Models\Quote;
use App\Models\WebsiteSetting;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class QuotePdfService
{
    public function pdfData(Quote $quote): array
    {
        $quote->load(['items', 'customer', 'flightBookingRequest', 'creator']);

        return [
            'quote' => $quote,
            'company' => WebsiteSetting::cached(),
            'contact' => ContactDetail::cached(),
            'issuedAt' => $quote->created_at,
        ];
    }

    public function pdfResponse(Quote $quote): Response
    {
        $pdf = Pdf::loadView('pdf.quote', $this->pdfData($quote))
            ->setPaper('a4');

        return $pdf->download('quote-'.$quote->quote_number.'.pdf');
    }

    public function streamResponse(Quote $quote): Response
    {
        $pdf = Pdf::loadView('pdf.quote', $this->pdfData($quote))
            ->setPaper('a4');

        return $pdf->stream('quote-'.$quote->quote_number.'.pdf');
    }
}
