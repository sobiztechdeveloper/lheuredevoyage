@extends('layouts.admin.app')

@section('title', 'Customer Reports')

@section('content')
<form method="GET" class="row g-2 mb-4">
    <div class="col-md-3"><input type="date" name="from" class="form-control" value="{{ $from }}"></div>
    <div class="col-md-3"><input type="date" name="to" class="form-control" value="{{ $to }}"></div>
    <div class="col-auto"><button class="btn btn-primary">Apply</button></div>
</form>
<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card p-3"><h6 class="text-muted">Total customers</h6><p class="h4 mb-0">{{ $report['summary']['total'] }}</p></div></div>
    <div class="col-md-4"><div class="card p-3"><h6 class="text-muted">New in period</h6><p class="h4 mb-0">{{ $report['summary']['in_period'] }}</p></div></div>
</div>
<div class="card mb-4">
    <div class="card-header">Top customers by bookings</div>
    <table class="table mb-0">
        <thead><tr><th>Name</th><th>Email</th><th>Bookings</th></tr></thead>
        <tbody>
            @foreach($report['top_customers'] as $u)
            <tr>
                <td><a href="{{ route('admin.users.show', $u) }}">{{ $u->name }}</a></td>
                <td>{{ $u->email }}</td>
                <td>{{ $u->bookings_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
<div class="card">
    <div class="card-header">Registrations by day</div>
    <ul class="list-group list-group-flush">
        @forelse($report['registrations'] as $row)
            <li class="list-group-item d-flex justify-content-between"><span>{{ $row->date }}</span><span>{{ $row->total }}</span></li>
        @empty
            <li class="list-group-item text-muted">No registrations in this period.</li>
        @endforelse
    </ul>
</div>
@endsection
