<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $booking->reference }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; }
        h1 { font-size: 20px; color: #162F65; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f5f5; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Invoice</h1>
    <p><strong>{{ $company->company_name ?? "L'Heure De Voyage" }}</strong></p>
    @if($company->company_address)<p>{{ $company->company_address }}</p>@endif
    @if($company->company_email)<p>{{ $company->company_email }}</p>@endif

    <p>Reference: <strong>{{ $booking->reference }}</strong><br>
    Date: {{ $issuedAt->format(config('date.display')) }}<br>
    Status: {{ ucfirst($booking->status) }}</p>

    <p><strong>Bill to</strong><br>
    {{ $booking->user?->name }}<br>
    {{ $booking->user?->email }}</p>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ class_basename($booking->bookable_type) }} — {{ $booking->bookable?->title ?? $booking->bookable?->name }}</td>
                <td class="text-right">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th class="text-right">Total</th>
                <th class="text-right">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>
