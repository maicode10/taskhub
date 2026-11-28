{{-- Use Mai's shared layout (navbar, colors, Bootstrap) --}}
@extends('layouts.app')

@section('content')

{{-- Success / error messages --}}
@include('tasks._messages')

<div class="card mb-4">
    <div class="card-header bg-purple text-white">Create Task</div>

    <div class="card-body">
        {{-- Sends the form to TaskController@store (route admin.tasks.store).
             onsubmit disables the button after the first click, so a double
             click can't create the same task twice. --}}
        <form action="{{ route('admin.tasks.store') }}" method="POST"
              onsubmit="let b = this.querySelector('button[type=submit]'); b.disabled = true; b.textContent = 'Saving...';">

            {{-- CSRF token: protects against fake forms from other sites.
                 Without it Laravel returns error 419. --}}
            @csrf

            {{-- TITLE --}}
            <div class="mb-3">
                <label class="form-label">Title</label>
                {{-- old('title') keeps what the admin typed if validation fails.
                     is-invalid turns the box red when there is an error. --}}
                <input type="text" name="title" value="{{ old('title') }}"
                       class="form-control @error('title') is-invalid @enderror">
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- DESCRIPTION (optional) --}}
            <div class="mb-3">
                <label class="form-label">Description (optional)</label>
                <textarea name="description" rows="3"
                          class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- ASSIGN TO: only active staff, sent from create() in the controller --}}
            <div class="mb-3">
                <label class="form-label">Assign to</label>
                <select name="assigned_to"
                        class="form-select @error('assigned_to') is-invalid @enderror">
                    <option value="">-- choose staff --</option>
                    @foreach ($staff as $person)
                        {{-- Re-select the same person if the form comes back with errors --}}
                        <option value="{{ $person->id }}"
                            {{ old('assigned_to') == $person->id ? 'selected' : '' }}>
                            {{ $person->name }}
                        </option>
                    @endforeach
                </select>
                @error('assigned_to')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                {{-- PRIORITY: same 3 values as the database and the validation rule --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Priority</label>
                    <select name="priority"
                            class="form-select @error('priority') is-invalid @enderror">
                        @foreach (['low', 'medium', 'high'] as $p)
                            {{-- "medium" is selected by default --}}
                            <option value="{{ $p }}"
                                {{ old('priority', 'medium') === $p ? 'selected' : '' }}>
                                {{ ucfirst($p) }}
                            </option>
                        @endforeach
                    </select>
                    @error('priority')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- DUE DATE: the server also checks that it is not in the past --}}
                <div class="col-md-6 mb-3">
                    <label class="form-label">Due date</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}"
                           class="form-control @error('due_date') is-invalid @enderror">
                    @error('due_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            {{-- type="submit" is what the onsubmit script looks for --}}
            <button type="submit" class="btn btn-purple">Create Task</button>
            <a href="{{ route('admin.tasks.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>

@endsection