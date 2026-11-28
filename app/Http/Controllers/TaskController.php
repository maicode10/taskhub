<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateProgressRequest;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    // ===== ADMIN =====

    public function index(Request $request)
    {
        // validate the search box too (every form needs validation)
        $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
        ], [
            'search.max' => 'Search text must not be longer than 100 characters.',
        ]);

        $search = $request->input('search');

        // Eloquent uses prepared statements, so the search text can't inject SQL
        $tasks = Task::with('assignee')
            ->when($search, fn ($query, $search) =>
                $query->where('title', 'like', "%{$search}%")
            )
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.tasks.index', compact('tasks', 'search'));
    }

    public function create()
    {
        $staff = User::where('role', 'staff')
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view('admin.tasks.create', compact('staff'));
    }

    public function store(StoreTaskRequest $request)
    {
        // validated() returns only fields that passed the rules (not all())
        $data = $request->validated();

        // set by the server so the form can't fake them
        $data['created_by'] = Auth::id();
        $data['status']     = 'pending';
        $data['progress']   = 0;

        try {
            Task::create($data);
        } catch (\Throwable $e) {
            Log::error('Task creation failed: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'Something went wrong. Please try again.');
        }

        return redirect()
            ->route('admin.tasks.index')
            ->with('success', 'Task created and assigned successfully.');
    }

    // approve a submitted task
    public function verify($id)
    {
        $task = Task::findOrFail($id);

        if ($task->status !== 'submitted') {
            return back()->with('error', 'Only submitted tasks can be verified.');
        }

        $task->update([
            'status'      => 'verified',
            'verified_at' => now(),
        ]);

        return back()->with('success', 'Task verified.');
    }

    // send a submitted task back to staff with remarks
    public function reject(Request $request, $id)
    {
        $data = $request->validate([
            'remarks' => ['required', 'string', 'max:500'],
        ], [
            'remarks.required' => 'Please explain why the task is rejected.',
            'remarks.max'      => 'Remarks must not be longer than 500 characters.',
        ]);

        $task = Task::findOrFail($id);

        if ($task->status !== 'submitted') {
            return back()->with('error', 'Only submitted tasks can be rejected.');
        }

        // remarks is encrypted automatically by the cast in the Task model
        $task->update([
            'status'  => 'rejected',
            'remarks' => $data['remarks'],
        ]);

        return back()->with('success', 'Task sent back to staff.');
    }

    // ===== STAFF =====

    // only the tasks assigned to the logged-in staff
    public function myTasks()
    {
        $tasks = Task::where('assigned_to', Auth::id())
            ->latest()
            ->paginate(10);

        return view('staff.tasks.index', compact('tasks'));
    }

    public function updateProgress(UpdateProgressRequest $request, $id)
    {
        // scoping to my own tasks blocks IDOR (changing the ID in the URL)
        $task = Task::where('assigned_to', Auth::id())->findOrFail($id);

        // submitted or verified tasks are locked
        if (in_array($task->status, ['submitted', 'verified'])) {
            return back()->with('error', 'This task can no longer be edited.');
        }

        $progress = $request->validated()['progress'];

        $task->update([
            'progress' => $progress,
            'status'   => $progress > 0 ? 'in_progress' : 'pending',
        ]);

        return back()->with('success', 'Progress updated.');
    }

    public function submit($id)
    {
        $task = Task::where('assigned_to', Auth::id())->findOrFail($id);

        if (! in_array($task->status, ['in_progress', 'rejected'])) {
            return back()->with('error', 'Update your progress before submitting.');
        }

        $task->update([
            'status'       => 'submitted',
            'progress'     => 100,
            'submitted_at' => now(),
        ]);

        return back()->with('success', 'Task submitted for verification.');
    }
}