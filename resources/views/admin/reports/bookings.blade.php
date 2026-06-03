@extends('layouts.admin.app')

@section('title', 'Booking Reports')

@section('content')
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-3"><input type="date" name="from" class="form-control" value="{{ $from }}"></div>
    <div class="col-md-3"><input type="date" name="to" class="form-control" value="{{ $to }}"></div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All statuses</option>
            @foreach(['pending','confirmed','cancelled','completed'] as $s)
                <option value="{{ $s }}" @selected($status === $s)>{{ ucfirst($s) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto"><button class="btn btn-primary">Apply</button></div>
</form>
<div class="row g-3 mb-4">
    @foreach($byStatus as $row)
    <div class="col-md-3">
        <div class="card p-3">
            <h6 class="text-muted">{{ ucfirst($row->status) }}</h6>
            <p class="h5 mb-0">{{ $row->total }} <small class="text-muted">(${{ number_format($row->amount ?? 0, 2) }})</small></p>
        </div>
    </div>
    @endforeach
</div>
<div class="card">
    <table class="table mb-0">
        <thead><tr><th>Reference</th><th>Customer</th><th>Product</th><th>Amount</th><th>Status</th><th>Date</th></tr></thead>
        <tbody>
            @foreach($bookings as $b)
            <tr>
                <td><a href="{{ route('admin.bookings.show', $b) }}">{{ $b->reference }}</a></td>
                <td>{{ $b->user?->name }}</td>
                <td>{{ $b->bookable?->title }}</td>
                <td>{{ $b->currency }} {{ number_format($b->total_amount, 2) }}</td>
                <td>{{ ucfirst($b->status) }}</td>
                <td>{{ $b->created_at->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<p class="mt-2 text-muted">{{ $bookings->count() }} booking(s) in selected period.</p>
@endsection
