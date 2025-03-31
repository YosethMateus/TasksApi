<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Task extends Model
{
    use SoftDeletes, HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tasks';
    protected $fillable = [
        'title',
        'description',
        'status',
        'created_by',
        'assigned_to',
        'due_date',
        'total_time_spent'
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function timeLogs()
    {
        return $this->hasMany(TaskTimeLog::class);
    }
    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }
    
}
