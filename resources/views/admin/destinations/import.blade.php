@extends('layouts.admin.app')
@section('title', 'Import Destinations')
@section('content')
<div class="admin-page-header mb-4">
    <div>
        <h1>Import Destinations</h1>
        <p class="text-muted small mb-0">Upload CSV files (OurAirports-compatible column names supported)</p>
    </div>
    <a href="{{ route('admin.destinations.index') }}" class="btn btn-admin-outline btn-sm">Back to list</a>
</div>

<div class="admin-form-card">
    <form method="POST" action="{{ route('admin.destinations.import.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Dataset Type</label>
                <select name="dataset" class="form-select" required>
                    @foreach($datasets as $dataset)
                        <option value="{{ $dataset }}">{{ ucfirst(str_replace('_', ' ', $dataset)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">CSV File</label>
                <input type="file" name="file" class="form-control" accept=".csv,text/csv" required>
            </div>
            <div class="col-12">
                <div class="alert alert-info small mb-0">
                    <strong>Column mapping:</strong> headers are matched automatically (e.g. <code>iata_code</code>, <code>municipality</code>, <code>iso_country</code> for airports).
                    Existing records are updated by <code>type + code</code>, or by <code>type + name</code> when code is empty.
                </div>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-admin-primary">Upload &amp; Import</button>
        </div>
    </form>
</div>
@endsection
