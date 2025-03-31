<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'created_by');
    }
    public function assignedTasks()
    {
        return $this->hasMany(Task::class, 'assigned_to');
    }
    public function timeLogs()
    {
        return $this->hasMany(TaskTimeLog::class, 'user_id');
    }
    public function files()
    {
        return $this->hasMany(TaskFile::class, 'user_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

}
