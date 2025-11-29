@extends('layouts.app')

@section('title', 'Permissions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Permissions</h1>
    <a href="{{ route('admin.permissions.create') }}" class="btn btn-primary">Create Permission</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($permissions as $permission)
                <tr>
                    <td>{{ $permission->id }}</td>
                    <td>{{ $permission->name }}</td>
                    <td>{{ $permission->slug }}</td>
                    <td>{{ $permission->description }}</td>
                    <td>
                        <a href="{{ route('admin.permissions.edit', $permission) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" class="d-inline">
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

