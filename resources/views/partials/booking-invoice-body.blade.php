<p><strong>{{ $company->company_name ?? "L'Heure De Voyage" }}</strong></p>
@if($company->company_address ?? null)<p class="mb-1">{{ $company->company_address }}</p>@endif
@if($company->company_email ?? null)<p class="mb-1">{{ $company->company_email }}</p>@endif
<hr>
<p class="mb-1"><strong>Reference:</strong> {{ $booking->reference }}</p>
<p class="mb-1"><strong>Issued:</strong> {{ $issuedAt->format(config('date.display')) }}</p>
<p class="mb-1"><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
<p class="mb-1"><strong>Customer:</strong> {{ $booking->user?->name }} ({{ $booking->user?->email }})</p>
<table class="table mt-3">
    <thead><tr><th>Item</th><th class="text-end">Amount</th></tr></thead>
    <tbody>
        <tr>
            <td>
                {{ $booking->bookableTypeLabel() }} — {{ $booking->bookable?->title ?? $booking->bookable?->name }}
                @if($booking->travelersLabel())<br><small>{{ $booking->travelersLabel() }}</small>@endif
            </td>
            <td class="text-end">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</td>
        </tr>
    </tbody>
    <tfoot>
        <tr><th class="text-end">Total</th><th class="text-end">{{ $booking->currency }} {{ number_format($booking->total_amount, 2) }}</th></tr>
    </tfoot>
</table>
