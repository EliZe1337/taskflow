<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'description', 'status', 'priority',
        'project_id', 'creator_id', 'assignee_id', 'parent_id',
        'due_date', 'estimated_hours', 'actual_hours', 'position',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    // ── Связи ────────────────────────────────────────────────

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class)->latest();
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'task_tag');
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assignee_id', $userId);
    }

    public function scopeOverdue($query)
    {
        return $query->whereNotNull('due_date')
                     ->where('due_date', '<', now())
                     ->whereNotIn('status', ['done', 'cancelled']);
    }

    public function scopeRootTasks($query)
    {
        return $query->whereNull('parent_id');
    }

    // ── Аксессоры ────────────────────────────────────────────

    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            'low'      => 'Низкий',
            'medium'   => 'Средний',
            'high'     => 'Высокий',
            'critical' => 'Критический',
            default    => $this->priority,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'todo'        => 'К выполнению',
            'in_progress' => 'В работе',
            'review'      => 'На проверке',
            'done'        => 'Выполнено',
            'cancelled'   => 'Отменено',
            default       => $this->status,
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date
            && $this->due_date->isPast()
            && !in_array($this->status, ['done', 'cancelled']);
    }
}
