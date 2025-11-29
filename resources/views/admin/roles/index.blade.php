@extends('layouts.app')

@section('title', 'Roles')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Roles</h1>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">Create Role</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Permissions</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($roles as $role)
                <tr>
                    <td>{{ $role->id }}</td>
                    <td>{{ $role->name }}</td>
                    <td>{{ $role->slug }}</td>
                    <td>
                        @foreach($role->permissions as $permission)
                            <span class="badge bg-info">{{ $permission->name }}</span>
                        @endforeach
                    </td>
                    <td>
                        <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline">
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

