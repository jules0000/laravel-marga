@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1>Dashboard</h1>
        <p>Welcome, {{ auth()->user()->name }}!</p>
        
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Your Roles</h5>
                        <ul>
                            @foreach(auth()->user()->roles as $role)
                                <li>{{ $role->name }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
                    <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Quick Actions</h5>
                        @if(auth()->user()->hasPermission('manage-webpages'))
                            <a href="{{ route('webpages.index') }}" class="btn btn-primary btn-sm mb-2">Manage Webpages</a><br>
                        @endif
                        @if(auth()->user()->hasRole('admin'))
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm mb-2">Manage Users</a><br>
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-primary btn-sm mb-2">Manage Roles</a><br>
                            <a href="{{ route('admin.permissions.index') }}" class="btn btn-primary btn-sm">Manage Permissions</a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

