<?php

namespace App\Providers;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Policies\ProjectPolicy;
use App\Policies\TaskPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Project::class => ProjectPolicy::class,
        Task::class    => TaskPolicy::class,
        User::class    => UserPolicy::class,
    ];

    public function boot(): void
    {
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }
    }
}
