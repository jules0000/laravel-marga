@extends('layouts.app')

@section('title', 'Welcome')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h2>Welcome to Laravel RBAC System</h2>
            </div>
            <div class="card-body">
                <p class="lead">A simple Laravel application with Role-Based Access Control (RBAC) system.</p>
                <p>This system includes:</p>
                <ul>
                    <li>User authentication</li>
                    <li>Role management</li>
                    <li>Permission management</li>
                    <li>Role-based access control</li>
                </ul>
                @guest
                <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection

