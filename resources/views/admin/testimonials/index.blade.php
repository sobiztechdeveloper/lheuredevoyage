@extends('layouts.admin.app')

@section('title', 'Testimonials')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2 class="h4 mb-0">Testimonials</h2>
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-primary btn-sm">Add Testimonial</a>
</div>
<div class="card">
    <table class="table mb-0">
        <thead><tr><th>Name</th><th>Rating</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @foreach($testimonials as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->rating }}/5</td>
                    <td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
                    <td class="text-end">
                        <a href="{{ route('admin.testimonials.edit', $item) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.testimonials.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $testimonials->links() }}
@endsection
