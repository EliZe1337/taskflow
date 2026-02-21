<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function view(User $user, Task $task): bool
    {
        if ($user->hasRole('admin')) return true;
        $project = $task->project;
        return $project->owner_id === $user->id
            || $project->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return !$user->hasRole('viewer');
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('viewer')) return false;
        // Менеджер проекта, владелец или исполнитель могут редактировать
        return $task->project->owner_id === $user->id
            || $task->assignee_id === $user->id
            || $task->creator_id === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->hasRole('admin')
            || $task->project->owner_id === $user->id
            || $task->creator_id === $user->id;
    }
}
