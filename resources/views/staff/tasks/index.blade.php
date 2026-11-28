@extends('layouts.app')

@section('content')

@include('tasks._messages')

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold text-purple mb-0">My Tasks</h2>
    <a href="{{ route('staff.dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
</div>

{{-- $tasks only has the tasks assigned to the logged-in staff
     (the controller filters with where('assigned_to', Auth::id())) --}}
@forelse ($tasks as $task)
    <div class="card mb-3">

        {{-- Card header: title and current status --}}
        <div class="card-header bg-purple text-white d-flex justify-content-between">
            <span>{{ $task->title }}</span>
            <span class="badge bg-light text-dark">{{ str_replace('_', ' ', $task->status) }}</span>
        </div>

        <div class="card-body">
            @if ($task->description)
                <p>{{ $task->description }}</p>
            @endif

            <p class="small text-muted mb-2">
                Priority: {{ ucfirst($task->priority) }} ·
                Due: {{ $task->due_date?->format('M d, Y') }}
            </p>

            {{-- Progress bar: the width is the progress number (0 to 100) --}}
            <div class="progress mb-3">
                <div class="progress-bar bg-success" style="width: {{ $task->progress }}%">
                    {{ $task->progress }}%
                </div>
            </div>

            {{-- If the admin rejected it, show the reason so staff know what to fix --}}
            @if ($task->status === 'rejected' && $task->remarks)
                <div class="alert alert-warning">
                    <strong>Admin remarks:</strong> {{ $task->remarks }}
                </div>
            @endif

            {{-- Staff can only edit while the task is pending, in progress, or rejected.
                 After submitting or verifying, the task is locked. --}}
            @if (in_array($task->status, ['pending', 'in_progress', 'rejected']))

                {{-- UPDATE PROGRESS: goes to TaskController@updateProgress --}}
                <form action="{{ route('staff.tasks.progress', $task->id) }}"
                      method="POST" class="d-flex gap-2 mb-2">
                    @csrf

                    {{-- HTML forms only send GET or POST, so this tells Laravel
                         the route is PATCH --}}
                    @method('PATCH')
                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                    <input type="number" name="progress" min="0" max="100"
                           value="{{ $task->progress }}" class="form-control w-25">
                    <button class="btn btn-purple">Update Progress</button>
                </form>

                {{-- Validation error from UpdateProgressRequest (for example 150) --}}
                @if ($errors->has('progress') && old('task_id') == $task->id)
                    <div class="text-danger small mb-2">{{ $errors->first('progress') }}</div>
                @endif

                {{-- SUBMIT: only after the staff has started working --}}
                @if (in_array($task->status, ['in_progress', 'rejected']))
                    <form action="{{ route('staff.tasks.submit', $task->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-success">Submit for Verification</button>
                    </form>
                @endif

            {{-- Locked states --}}
            @elseif ($task->status === 'submitted')
                <p class="text-muted mb-0">Waiting for admin verification.</p>
            @else
                <p class="text-success mb-0">Verified. Good job!</p>
            @endif
        </div>
    </div>
@empty
    <div class="card"><div class="card-body text-muted">No tasks assigned to you yet.</div></div>
@endforelse

{{-- Page numbers --}}
{{ $tasks->links('pagination::bootstrap-5') }}

@endsection