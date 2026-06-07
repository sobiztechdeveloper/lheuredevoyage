<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quote {{ $quote->quote_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #333; margin: 24px; }
        h1 { font-size: 22px; color: #162F65; margin: 0 0 4px; }
        h2 { font-size: 14px; color: #3361AC; margin: 20px 0 8px; border-bottom: 2px solid #E8AF30; padding-bottom: 4px; }
        .brand { margin-bottom: 20px; }
        .brand img { max-height: 48px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #162F65; color: #fff; font-size: 10px; }
        .text-right { text-align: right; }
        .totals { margin-top: 12px; width: 45%; margin-left: auto; }
        .totals td { border: none; padding: 4px 8px; }
        .grand { font-size: 14px; font-weight: bold; color: #162F65; }
        .muted { color: #666; font-size: 10px; }
        .terms { margin-top: 24px; font-size: 9px; color: #555; }
        .accent { color: #E8AF30; }
    </style>
</head>
<body>
    <div class="brand">
        @if($company->logo_url ?? null)
            <img src="{{ public_path(str_replace(asset(''), '', $company->logo_url)) }}" alt="Logo" onerror="this.style.display='none'">
        @endif
        <h1>{{ $company->company_name ?? "L'Heure De Voyage" }}</h1>
        @if($company->company_address)<p class="muted">{{ $company->company_address }}</p>@endif
        @if($company->company_phone)<p class="muted">{{ $company->company_phone }}</p>@endif
        @if($company->company_email)<p class="muted">{{ $company->company_email }}</p>@endif
    </div>

    <h2>Quotation</h2>
    <p>
        <strong>Quote number:</strong> {{ $quote->quote_number }}<br>
        <strong>Date:</strong> {{ $issuedAt->format(config('date.display')) }}<br>
        <strong>Valid until:</strong> <span class="accent">{{ $quote->valid_until->format(config('date.display')) }}</span><br>
        <strong>Status:</strong> {{ $quote->statusLabel() }}
    </p>

    <h2>Prepared for</h2>
    <p>
        @if($quote->customer)
            <strong>{{ $quote->customer->name }}</strong><br>
            {{ $quote->customer->email }}
        @elseif($quote->flightBookingRequest)
            <strong>{{ $quote->flightBookingRequest->contact_name }}</strong><br>
            {{ $quote->flightBookingRequest->email }}
        @else
            Valued Customer
        @endif
    </p>

    <h2>{{ $quote->title }}</h2>
    @if($quote->description)<p class="muted" style="white-space:pre-wrap">{{ $quote->description }}</p>@endif

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Unit price</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($quote->items as $item)
            <tr>
                <td>{{ $item->item_name }}@if($item->description)<br><span class="muted">{{ $item->description }}</span>@endif</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr><td class="text-right">Subtotal</td><td class="text-right">{{ strtoupper($quote->currency) }} {{ number_format($quote->amount, 2) }}</td></tr>
        <tr><td class="text-right">Tax</td><td class="text-right">{{ number_format($quote->tax_amount ?? 0, 2) }}</td></tr>
        <tr><td class="text-right">Service fee</td><td class="text-right">{{ number_format($quote->service_fee ?? 0, 2) }}</td></tr>
        <tr class="grand"><td class="text-right">Grand total</td><td class="text-right">{{ strtoupper($quote->currency) }} {{ number_format($quote->total_amount, 2) }}</td></tr>
    </table>

    @if($quote->notes)
        <h2>Notes</h2>
        <p>{{ $quote->notes }}</p>
    @endif

    <div class="terms">
        <h2>Terms &amp; Conditions</h2>
        <p>This quotation is for information only and does not constitute a confirmed booking until accepted by our travel consultant. Fares and availability are subject to change until ticketed. No payment is processed online — our team will contact you to arrange payment and issuance. Quote reference: {{ $quote->quote_number }}.</p>
        @if($contact?->whatsapp_number)<p>WhatsApp: {{ $contact->whatsapp_number }}</p>@endif
    </div>
</body>
</html>
