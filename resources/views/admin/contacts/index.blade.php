@extends('layouts.admin.app')

@section('title', 'Contact Messages')

@section('content')
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><input type="search" name="q" class="form-control" placeholder="Search" value="{{ $search }}"></div>
    <div class="col-auto">
        <label class="form-check-label"><input type="checkbox" name="unread" value="1" class="form-check-input" @checked($unreadOnly)> Unread only</label>
    </div>
    <div class="col-auto"><button class="btn btn-primary">Filter</button></div>
</form>
<div class="card">
    <table class="table mb-0">
        <thead>
            <tr><th>Name</th><th>Email</th><th>Subject</th><th>Date</th><th>Read</th><th></th></tr>
        </thead>
        <tbody>
            @foreach($contacts as $contact)
                <tr>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->email }}</td>
                    <td>{{ $contact->subject ?? '—' }}</td>
                    <td>{{ $contact->created_at->format('M d, Y') }}</td>
                    <td>@if($contact->read_at)<span class="badge bg-success">Read</span>@else<span class="badge bg-warning">New</span>@endif</td>
                    <td class="text-end">
                        <a href="{{ route('admin.inquiries.show', $contact) }}" class="btn btn-sm btn-outline-primary">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $contacts->links() }}
@endsection
