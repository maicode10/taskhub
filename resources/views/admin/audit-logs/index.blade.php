@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold text-purple">Audit Logs</h2>
        <p class="text-muted mb-0">
            Monitor user activities and system actions.
        </p>
    </div>

    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
        Back to Dashboard
    </a>
</div>

{{-- Search --}}
<div class="card mb-4">
    <div class="card-body">
        <form action="{{ route('admin.audit-logs.index') }}" method="GET"
              class="d-flex gap-2">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search action, description, or IP address..."
                value="{{ request('search') }}"
            >

            <button type="submit" class="btn btn-purple">
                Search
            </button>

            <a href="{{ route('admin.audit-logs.index') }}"
               class="btn btn-outline-secondary">
                Clear
            </a>
        </form>
    </div>
</div>

{{-- Audit log records --}}
<div class="card">
    <div class="card-top p-3">
        <h5 class="mb-0">Recorded Activities</h5>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date and Time</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($logs as $log)
                        <tr>
                            <td>
                                {{ $log->created_at?->format('M d, Y h:i A') ?? 'N/A' }}
                            </td>

                            <td>
                                {{ $log->user?->name ?? 'Unknown User' }}
                            </td>

                            <td>
                                <span class="badge bg-primary">
                                    {{ $log->action }}
                                </span>
                            </td>

                            <td>{{ $log->description }}</td>

                            <td>{{ $log->ip_address ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No audit log records found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>

@endsection
