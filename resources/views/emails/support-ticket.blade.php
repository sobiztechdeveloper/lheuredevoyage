<x-mail::message>
@if($type === 'created')
# Support ticket received

Your ticket **{{ $ticket->reference }}** has been submitted.

**Subject:** {{ $ticket->subject }}

We will respond as soon as possible.
@elseif($type === 'reply')
# New reply on your ticket

**{{ $ticket->reference }}** — {{ $ticket->subject }}

@if($reply)
{{ $reply->message }}
@endif
@elseif($type === 'admin_new')
# New support ticket

**{{ $ticket->reference }}** from {{ $ticket->user?->name }} ({{ $ticket->user?->email }})

**Subject:** {{ $ticket->subject }}  
**Category:** {{ $ticket->category }}  
**Priority:** {{ $ticket->priority }}
@elseif($type === 'admin_reply')
# Customer replied

**{{ $ticket->reference }}** — {{ $ticket->subject }}

@if($reply)
{{ $reply->message }}
@endif
@endif

<x-mail::button :url="config('app.url')">
View site
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
