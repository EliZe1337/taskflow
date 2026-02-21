<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Project;
use App\Models\Tag;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $tasks = Task::query()
                     ->whereHas('project', fn($q) => $q->forUser($user))
                     ->with(['project', 'assignee', 'tags'])
                     ->when($request->status, fn($q) => $q->where('status', $request->status))
                     ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
                     ->when($request->project_id, fn($q) => $q->where('project_id', $request->project_id))
                     ->when($request->my_tasks, fn($q) => $q->assignedTo($user->id))
                     ->when($request->overdue, fn($q) => $q->overdue())
                     ->rootTasks()
                     ->latest()
                     ->paginate(15);

        $projects = Project::forUser($user)->active()->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'projects'));
    }

    public function create(Request $request): View
    {
        $projects = Project::forUser($request->user())->active()->orderBy('name')->get();
        $users    = User::active()->orderBy('name')->get();
        $tags     = Tag::orderBy('name')->get();
        $selectedProject = $request->project_id
            ? Project::find($request->project_id)
            : null;

        return view('tasks.create', compact('projects', 'users', 'tags', 'selectedProject'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'status'           => 'required|in:todo,in_progress,review,done,cancelled',
            'priority'         => 'required|in:low,medium,high,critical',
            'project_id'       => 'required|exists:projects,id',
            'assignee_id'      => 'nullable|exists:users,id',
            'parent_id'        => 'nullable|exists:tasks,id',
            'due_date'         => 'nullable|date',
            'estimated_hours'  => 'nullable|integer|min:1|max:999',
            'tags'             => 'nullable|array',
            'tags.*'           => 'exists:tags,id',
        ]);

        $task = Task::create([
            ...$data,
            'creator_id' => auth()->id(),
        ]);

        if (!empty($data['tags'])) {
            $task->tags()->attach($data['tags']);
        }

        $this->logActivity($task, 'created', 'Задача создана');

        return redirect()->route('tasks.show', $task)
                         ->with('success', 'Задача создана!');
    }

    public function show(Task $task): View
    {
        $this->authorize('view', $task);

        $task->load([
            'project', 'creator', 'assignee', 'tags',
            'comments.user', 'attachments.user',
            'activityLogs.user', 'subtasks.assignee',
            'parent',
        ]);

        $users = User::active()->orderBy('name')->get();

        return view('tasks.show', compact('task', 'users'));
    }

    public function edit(Task $task): View
    {
        $this->authorize('update', $task);

        $projects = Project::forUser(auth()->user())->active()->orderBy('name')->get();
        $users    = User::active()->orderBy('name')->get();
        $tags     = Tag::orderBy('name')->get();
        $task->load('tags', 'project');

        return view('tasks.edit', compact('task', 'projects', 'users', 'tags'));
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task);

        $data = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'status'          => 'required|in:todo,in_progress,review,done,cancelled',
            'priority'        => 'required|in:low,medium,high,critical',
            'assignee_id'     => 'nullable|exists:users,id',
            'due_date'        => 'nullable|date',
            'estimated_hours' => 'nullable|integer|min:1|max:999',
            'actual_hours'    => 'nullable|integer|min:0|max:9999',
            'tags'            => 'nullable|array',
            'tags.*'          => 'exists:tags,id',
        ]);

        $oldStatus = $task->status;
        $task->update($data);
        $task->tags()->sync($data['tags'] ?? []);

        if ($oldStatus !== $task->status) {
            $this->logActivity(
                $task, 'status_changed',
                "Статус изменён: {$oldStatus} → {$task->status}",
                ['old_status' => $oldStatus, 'new_status' => $task->status]
            );
        } else {
            $this->logActivity($task, 'updated', 'Задача обновлена');
        }

        return redirect()->route('tasks.show', $task)
                         ->with('success', 'Задача обновлена!');
    }

    // AJAX: быстрое обновление статуса
    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task);

        $request->validate(['status' => 'required|in:todo,in_progress,review,done,cancelled']);

        $old = $task->status;
        $task->update(['status' => $request->status]);

        $this->logActivity(
            $task, 'status_changed',
            "Статус: {$old} → {$request->status}",
            ['old' => $old, 'new' => $request->status]
        );

        return response()->json(['success' => true, 'status' => $task->status_label]);
    }

    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('delete', $task);
        $projectId = $task->project_id;
        $task->delete();

        return redirect()->route('projects.show', $projectId)
                         ->with('success', 'Задача удалена.');
    }

    private function logActivity(Task $task, string $event, string $desc, array $props = []): void
    {
        ActivityLog::create([
            'event'       => $event,
            'description' => $desc,
            'task_id'     => $task->id,
            'user_id'     => auth()->id(),
            'properties'  => $props ?: null,
            'created_at'  => now(),
        ]);
    }
}
