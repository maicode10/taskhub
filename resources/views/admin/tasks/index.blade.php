@extends('layouts.app')

@section('content')

@include('tasks._messages')

{{-- Page title and the buttons --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="fw-bold text-purple mb-0">All Tasks</h2>

    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
        <a href="{{ route('admin.tasks.create') }}" class="btn btn-purple">+ New Task</a>
    </div>
</div>

{{-- SEARCH BOX: uses GET, so the text goes in the URL (?search=...).
     The controller searches with Eloquent (prepared statements),
     so SQL injection typed here does nothing. --}}
<form action="{{ route('admin.tasks.index') }}" method="GET" class="mb-3 d-flex gap-2">
    <input type="text" name="search" value="{{ $search }}"
           class="form-control" placeholder="Search by title">
    <button class="btn btn-purple">Search</button>
</form>

{{-- Error from the search validation (for example, text longer than 100 characters) --}}
@error('search')
    <div class="text-danger small mb-2">{{ $message }}</div>
@enderror

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Assigned to</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Due</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            {{-- Loop through the tasks. @empty runs if there are none. --}}
            @forelse ($tasks as $task)
                <tr>
                    {{-- {{ }} escapes the text, so <script> shows as plain text (blocks XSS) --}}
                    <td>{{ $task->title }}</td>

                    {{-- assignee is the relationship in the Task model.
                         "?? 'N/A'" avoids an error if the user was deleted. --}}
                    <td>{{ $task->assignee->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($task->priority) }}</td>

                    {{-- Turns "in_progress" into "in progress" --}}
                    <td><span class="badge bg-secondary">{{ str_replace('_', ' ', $task->status) }}</span></td>
                    <td>{{ $task->progress }}%</td>

                    {{-- ?-> avoids an error if due_date is empty --}}
                    <td>{{ $task->due_date?->format('M d, Y') }}</td>

                    <td>
                        {{-- Verify and Reject only appear when staff already submitted --}}
                        @if ($task->status === 'submitted')

                            {{-- VERIFY: approves the task --}}
                            <form action="{{ route('admin.tasks.verify', $task->id) }}"
                                  method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm">Verify</button>
                            </form>

                            {{-- REJECT: needs a reason. The reason is saved in "remarks"
                                 (encrypted by the Task model). "required" and "maxlength"
                                 help the user, but the controller validates it again. --}}
                            <form action="{{ route('admin.tasks.reject', $task->id) }}"
                                  method="POST" class="d-flex gap-1 mt-1">
                                @csrf
                                <input type="text" name="remarks"
                                       class="form-control form-control-sm"
                                       placeholder="Reason" required maxlength="500">
                                <button class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No tasks found.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Page numbers (10 tasks per page, set in the controller) --}}
<div class="mt-3">
    {{ $tasks->links('pagination::bootstrap-5') }}
</div>

@endsection