
@extends('layouts.app')

@section('content')

<div class="mb-4">
    <h2 class="fw-bold text-purple">Admin Dashboard</h2>
    <p class="text-muted">
        Welcome to the TaskHub Administration Panel.
    </p>
</div>

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

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold">Administration</h5>
                <p class="text-muted">
                    Manage and monitor the TaskHub system.
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="fw-bold">Audit Logs</h5>
                <p class="text-muted">
                    Monitor recorded user activities and system actions.
                </p>

                <a href="{{ route('admin.audit-logs.index') }}"
                   class="btn btn-purple">
                    View Audit Logs
                </a>
            </div>
        </div>
    </div>

</div>

@endsection
