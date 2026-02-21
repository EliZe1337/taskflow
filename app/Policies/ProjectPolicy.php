<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // Все видят список (фильтруется через scope)
    }

    public function view(User $user, Project $project): bool
    {
        return $user->hasRole('admin')
            || $project->owner_id === $user->id
            || $project->members()->where('user_id', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'manager']);
    }

    public function update(User $user, Project $project): bool
    {
        return $user->hasRole('admin')
            || $project->owner_id === $user->id
            || $project->members()->where('user_id', $user->id)
                       ->where('role', 'manager')->exists();
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->hasRole('admin') || $project->owner_id === $user->id;
    }
}
