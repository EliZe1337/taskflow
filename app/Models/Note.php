<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Note extends Model
{

    use HasFactory;

    protected $fillable = ['title', 'body', 'user_id'];

    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }


}
