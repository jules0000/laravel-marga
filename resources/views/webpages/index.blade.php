@extends('layouts.app')

@section('title', 'Webpages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Webpages</h1>
    <a href="{{ route('webpages.create') }}" class="btn btn-primary">Create Webpage</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Slug</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($webpages as $webpage)
                <tr>
                    <td>{{ $webpage->id }}</td>
                    <td>{{ $webpage->title }}</td>
                    <td><span class="badge bg-info">{{ ucfirst($webpage->type) }}</span></td>
                    <td>{{ $webpage->slug }}</td>
                    <td>
                        @if($webpage->is_published)
                            <span class="badge bg-success">Published</span>
                        @else
                            <span class="badge bg-secondary">Draft</span>
                        @endif
                    </td>
                    <td>{{ $webpage->creator->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('webpages.show', $webpage) }}" class="btn btn-sm btn-info" target="_blank">View</a>
                        <a href="{{ route('webpages.edit', $webpage) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('webpages.destroy', $webpage) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

