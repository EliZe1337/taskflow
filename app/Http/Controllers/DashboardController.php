<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        // Проекты пользователя
        $projects = Project::forUser($user)
                           ->active()
                           ->with(['owner', 'tasks'])
                           ->latest()
                           ->take(6)
                           ->get();

        // Мои задачи
        $myTasks = Task::assignedTo($user->id)
                       ->whereNotIn('status', ['done', 'cancelled'])
                       ->with(['project', 'assignee'])
                       ->orderByRaw("FIELD(priority, 'critical','high','medium','low')")
                       ->take(10)
                       ->get();

        // Просроченные задачи
        $overdueTasks = Task::assignedTo($user->id)
                            ->overdue()
                            ->with('project')
                            ->count();

        // Статистика
        $stats = [
            'total_projects'  => Project::forUser($user)->count(),
            'active_projects' => Project::forUser($user)->active()->count(),
            'my_tasks'        => Task::assignedTo($user->id)->whereNotIn('status', ['done', 'cancelled'])->count(),
            'done_this_week'  => Task::assignedTo($user->id)
                                     ->where('status', 'done')
                                     ->where('updated_at', '>=', now()->startOfWeek())
                                     ->count(),
            'overdue'         => $overdueTasks,
        ];

        return view('dashboard.index', compact('projects', 'myTasks', 'stats'));
    }
}
