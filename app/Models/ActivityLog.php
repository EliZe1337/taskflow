<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['event', 'description', 'task_id', 'user_id', 'properties'];

    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    const UPDATED_AT = null;

    public function task(): BelongsTo { return $this->belongsTo(Task::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
