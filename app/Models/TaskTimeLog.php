<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class TaskTimeLog extends Model
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'task_time_logs';
    protected $fillable = [
        'task_id',
        'user_id',
        'minutes'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
