@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">

        <div class="card">
            <div class="card-top py-2 px-3">
                <span class="fw-semibold">Dashboard</span>
            </div>

            <div class="card-body p-4">
                <h5 class="mb-1">Welcome, {{ auth()->user()->name }}</h5>
                <p class="text-muted small mb-4">
                    Role: {{ auth()->user()->role }} · Signed in at {{ now()->format('M d, Y g:i A') }}
                </p>

                <a href="{{ route('password.form') }}" class="btn btn-purple btn-sm">
                    Change Password
                </a>
            </div>
        </div>

        <p class="text-muted small mt-3">
            Note: this page is temporary. The admin and staff dashboards will replace it.
        </p>

    </div>
</div>
@endsection
