<?php

namespace App\Models\Project;

use App\Models\Task\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'status',
        'start_date',
        'end_date',
        'priority'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'project_users');
    }

    public function scopeForUser($query)
    {
        $userId = Auth::id();
        return $query->where('user_id', $userId)
            ->orWhereHas('members', function($q) use ($userId) {
                $q->where('user_id', $userId);
            });
    }
}

