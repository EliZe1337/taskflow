<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        $projects = Project::forUser($user)
                           ->with(['owner', 'members', 'tasks'])
                           ->withCount(['tasks', 'tasks as done_tasks_count' => fn($q) => $q->where('status', 'done')])
                           ->when($request->status, fn($q) => $q->where('status', $request->status))
                           ->when($request->search, fn($q) => $q->where('name', 'like', "%{$request->search}%"))
                           ->latest()
                           ->paginate(9);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);
        $users = User::active()->orderBy('name')->get();
        return view('projects.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'color'       => 'required|string|size:7',
            'status'      => 'required|in:active,on_hold,completed,archived',
            'start_date'  => 'nullable|date',
            'due_date'    => 'nullable|date|after_or_equal:start_date',
            'members'     => 'nullable|array',
            'members.*'   => 'exists:users,id',
        ]);

        $project = Project::create([
            ...$data,
            'owner_id' => auth()->id(),
        ]);

        // Добавляем участников
        if (!empty($data['members'])) {
            $membersData = collect($data['members'])->mapWithKeys(
                fn($id) => [$id => ['role' => 'developer']]
            )->toArray();
            $project->members()->attach($membersData);
        }

        return redirect()->route('projects.show', $project)
                         ->with('success', 'Проект успешно создан!');
    }

    public function show(Project $project, Request $request): View
    {
        $this->authorize('view', $project);

        $tasks = $project->tasks()
                         ->rootTasks()
                         ->with(['assignee', 'tags', 'subtasks'])
                         ->when($request->status, fn($q) => $q->where('status', $request->status))
                         ->when($request->priority, fn($q) => $q->where('priority', $request->priority))
                         ->when($request->assignee, fn($q) => $q->where('assignee_id', $request->assignee))
                         ->orderBy('position')
                         ->get()
                         ->groupBy('status');

        $members = $project->members()->get()->merge(collect([$project->owner]));
        $stats   = [
            'total'       => $project->tasks()->count(),
            'todo'        => $project->tasks()->where('status', 'todo')->count(),
            'in_progress' => $project->tasks()->where('status', 'in_progress')->count(),
            'review'      => $project->tasks()->where('status', 'review')->count(),
            'done'        => $project->tasks()->where('status', 'done')->count(),
        ];

        return view('projects.show', compact('project', 'tasks', 'members', 'stats'));
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);
        $users = User::active()->orderBy('name')->get();
        $project->load('members');
        return view('projects.edit', compact('project', 'users'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'color'       => 'required|string|size:7',
            'status'      => 'required|in:active,on_hold,completed,archived',
            'start_date'  => 'nullable|date',
            'due_date'    => 'nullable|date|after_or_equal:start_date',
            'members'     => 'nullable|array',
            'members.*'   => 'exists:users,id',
        ]);

        $project->update($data);

        // Синхронизируем участников (владелец остаётся)
        $membersData = collect($data['members'] ?? [])->mapWithKeys(
            fn($id) => [$id => ['role' => 'developer']]
        )->toArray();
        $project->members()->sync($membersData);

        return redirect()->route('projects.show', $project)
                         ->with('success', 'Проект обновлён!');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('projects.index')
                         ->with('success', 'Проект удалён.');
    }
}
