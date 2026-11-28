@extends('layouts.app')

@section('content')

{{-- hover effect so the cards look clickable --}}
<style>
    .card-link { transition: transform .15s, box-shadow .15s; }
    .card-link:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.12) !important; }
</style>

<div class="card mb-4">
    <div class="card-header bg-purple text-white">
        Admin Dashboard
    </div>

    <div class="card-body">
        <h5>Welcome, {{ auth()->user()->name }}</h5>

        <p class="text-muted">
            Role: {{ auth()->user()->role }}
        </p>

        <a href="{{ route('password.form') }}" class="btn btn-purple">
            Change Password
        </a>
    </div>
</div>

{{-- Intrusion alert (Eric's part) --}}
@if ($failedAttempts >= 5)
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Intrusion Alert!</strong>
        {{ $failedAttempts }} failed login attempts were detected
        within the last 15 minutes.

        Please review the login activity.

        <a href="{{ route('admin.audit-logs.index') }}" class="alert-link">
            View Audit Logs
        </a>

        <button type="button" class="btn-close"
                data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="row g-3">

    {{-- Tasks card (Member 3): the whole box is clickable --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100 card-link">
            <div class="card-body">
                <h5 class="fw-bold">Tasks</h5>
                <p class="text-muted mb-0">
                    Create, assign, and verify tasks.
                </p>
                {{-- stretched-link makes this link cover the whole card --}}
                <a href="{{ route('admin.tasks.index') }}"
                   class="stretched-link" aria-label="Manage Tasks"></a>
            </div>
        </div>
    </div>

    {{-- Audit Logs card: also clickable --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100 card-link">
            <div class="card-body">
                <h5 class="fw-bold">Audit Logs</h5>
                <p class="text-muted mb-0">
                    Monitor recorded user activities and system actions.
                </p>
                <a href="{{ route('admin.audit-logs.index') }}"
                   class="stretched-link" aria-label="View Audit Logs"></a>
            </div>
        </div>
    </div>

</div>

@endsection