<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'color', 'status',
        'start_date', 'due_date', 'owner_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'due_date'   => 'date',
    ];

    // ── Связи ────────────────────────────────────────────────

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // ── Scopes ───────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForUser($query, User $user)
    {
        // Проекты где пользователь — владелец или участник
        return $query->where('owner_id', $user->id)
                     ->orWhereHas('members', fn($q) => $q->where('user_id', $user->id));
    }

    // ── Аксессоры ────────────────────────────────────────────

    public function getProgressAttribute(): int
    {
        $total = $this->tasks()->count();
        if ($total === 0) return 0;

        $done = $this->tasks()->where('status', 'done')->count();
        return (int) round($done / $total * 100);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'active'    => 'Активный',
            'on_hold'   => 'На паузе',
            'completed' => 'Завершён',
            'archived'  => 'Архив',
            default     => $this->status,
        };
    }
}
