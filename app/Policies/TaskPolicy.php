<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    public function assign(User $user, Task $task): bool
    {
        return $user->id === $task->created_by && $task->status === 'pending';
    }
   
    public function delete(User $user, Task $task): bool
    {
        return $user->id === $task->created_by;
    }

    public function restore(User $user, Task $task): bool
    {
        return $user->id === $task->created_by;
    }

    public function timeLog(User $user, Task $task): bool
    {
        return $user->id === $task->assigned_to;
    }   

    public function uploadFile(User $user, Task $task): bool
    {
        return $user->id === $task->assigned_to || $user->id === $task->created_by;
    }

}
