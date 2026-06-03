<x-mail::message>
@if($type === 'sent')
# Your travel quote is ready

**Quote number:** {{ $quote->quote_number }}  
**Title:** {{ $quote->title }}  
**Total:** {{ strtoupper($quote->currency) }} {{ number_format($quote->total_amount, 2) }}  
**Valid until:** {{ $quote->valid_until->format('M d, Y') }}

Please review the quote and accept or reject it before the validity date.
@elseif($type === 'expired')
# Quote expired

Quote **{{ $quote->quote_number }}** is no longer valid (expired {{ $quote->valid_until->format('M d, Y') }}).

Contact our travel consultants if you need a new quotation.
@elseif($type === 'accepted')
# Quote accepted

Thank you for accepting quote **{{ $quote->quote_number }}**.

Our travel consultants will contact you shortly to complete manual ticketing and arrangements.

**Total:** {{ strtoupper($quote->currency) }} {{ number_format($quote->total_amount, 2) }}
@elseif($type === 'rejected')
# Quote declined

We have recorded your response for quote **{{ $quote->quote_number }}**.

Contact us if you would like a revised quotation.
@elseif($type === 'admin_accepted')
# Quote accepted by customer

**{{ $quote->quote_number }}** — {{ $quote->customer?->name ?? 'Customer' }}

**Total:** {{ strtoupper($quote->currency) }} {{ number_format($quote->total_amount, 2) }}
@elseif($type === 'admin_rejected')
# Quote rejected by customer

**{{ $quote->quote_number }}** — {{ $quote->customer?->name ?? 'Customer' }}
@endif

@if($type === 'sent' && $quote->customer_id)
<x-mail::button :url="route('my-quotes.show', $quote)">
View quote
</x-mail::button>
@elseif($type === 'admin_accepted' || $type === 'admin_rejected')
<x-mail::button :url="route('admin.quotes.show', $quote)">
View in admin
</x-mail::button>
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
