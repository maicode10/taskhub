
@extends('layouts.app')

@section('content')

<div class="card mb-4">
    <div class="card-header bg-purple text-white">
        Dashboard
    </div>

    <div class="card-body">
        <h5>Welcome, {{ auth()->user()->name }}</h5>

        <p class="text-muted">
            Role: {{ auth()->user()->role }}
        </p>

        <a href="{{ route('password.form') }}"
           class="btn btn-purple">
            Change Password
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-purple text-white">
        Dashboard
    </div>

    <div class="card-body">
        <h5>Welcome, {{ auth()->user()->name }}</h5>

        <p class="text-muted">
            Role: {{ auth()->user()->role }}
        </p>

        <a href="{{ route('password.form') }}"
           class="btn btn-purple">
            Change Password
        </a>
    </div>
</div>

<div class="mb-4">
    <h2 class="fw-bold text-purple">Staff Dashboard</h2>
    <p class="text-muted">
        Welcome to TaskHub. You are logged in as Staff.
    </p>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <h5 class="fw-bold">Staff Workspace</h5>
        <p class="text-muted">
            Access your assigned tasks and monitor your work progress.
        </p>
    </div>
</div>

@endsection
