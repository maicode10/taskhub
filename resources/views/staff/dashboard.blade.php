@extends('layouts.app')

@section('content')

<style>
    .card-link { transition: transform .15s, box-shadow .15s; }
    .card-link:hover { transform: translateY(-3px); box-shadow: 0 .5rem 1rem rgba(0,0,0,.12) !important; }
</style>

<div class="card mb-4">
    <div class="card-header bg-purple text-white">
        Staff Dashboard
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

<div class="row g-3">

    {{-- My Tasks card (Member 3): the whole box is clickable --}}
    <div class="col-md-6">
        <div class="card shadow-sm h-100 card-link">
            <div class="card-body">
                <h5 class="fw-bold">My Tasks</h5>
                <p class="text-muted mb-0">
                    See your assigned tasks, update your progress, and submit them.
                </p>
                <a href="{{ route('staff.tasks.index') }}"
                   class="stretched-link" aria-label="Open My Tasks"></a>
            </div>
        </div>
    </div>

</div>

@endsection