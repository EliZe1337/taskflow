<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Prompts\Note;

class notes extends Model
{

    use HasFactory, Notifiable, HasRoles;
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }


}
