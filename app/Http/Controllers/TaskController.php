<?php

namespace App\Http\Controllers;

use App\Mail\TaskMail;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    use AuthorizesRequests;
    public function index(Request $request)
    {
        $tasks = Task::all();
        
        return response()->json([
            'tasks' => $tasks
        ]);
    }

    public function store(Request $request)
    {
        // Validamos datos
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|in:pending,in_progress,completed',
            'due_date' => 'required|date',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'total_time_spent' => 'nullable|integer|min:0',
        ]);

        // Creaamos la tarea
        $task = Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'pending',
            'due_date' => $request->due_date,
            'created_by' => Auth::id(), // ID del usuario autenticado
            'assigned_to' => $request->assigned_to,
            'total_time_spent' => $request->total_time_spent ?? 0,
        ]);

        return response()->json([
            'message' => 'Tarea creada exitosamente',
            'task' => $task
        ], 201);
    }

    public function getTask($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }
        
        return response()->json([
            'tasks' => $task
        ]);
    }

    public function update(Request $request,$id)
    {
        $task = Task::find($id);
        // Verificamos si la tarea existe
        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }

        // Verifica si el usuario tiene permiso para asignar
        $this->authorize('assign', $task);

        // Validamos datos
        $request->validate([
            'assigned_to' => 'required|integer|exists:users,id',
        ], [
            'assigned_to.required' => 'El campo "assigned_to" es obligatorio.',
            'assigned_to.integer' => 'El campo "assigned_to" debe ser un número entero.',
            'assigned_to.exists' => 'El usuario seleccionado no existe en nuestros registros.',
        ]);
        // Actualizamos la tarea
        $task->update([
            'assigned_to' => $request->assigned_to,
        ]);

        Mail::to('yoseth.m97@gmail.com')->send(new TaskMail($task));

        return response()->json([
            'message' => 'Usuario '.$task->assignedTo->name.' asignado a la tarea',
            'task' => $task
        ]);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
        // Verificamos si la tarea existe
        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }

        // Verifica si el usuario tiene permiso para eliminar
        $this->authorize('delete', $task);

        // Eliminamos la tarea
        $task->delete();

        return response()->json([
            'message' => 'Tarea eliminada exitosamente'
        ]);
    }

    public function restore($id)
    {
        $task = Task::withTrashed()->find($id);
        // Verificamos si la tarea existe
        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }

        // Verifica si el usuario tiene permiso para restaurar
        $this->authorize('restore', $task);

        // Restauramos la tarea
        $task->restore();

        return response()->json([
            'message' => 'Tarea restaurada exitosamente'
        ]);
    }

    public function addComment(Request $request, $id)
    {
        $task = Task::find($id);
        // Verificamos si la tarea existe
        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }

        // Validamos datos
        $request->validate([
            'comment' => 'required|string|max:255',
        ]);

        // Agregamos el comentario
        $task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $request->comment,
        ]);

        return response()->json([
            'message' => 'Comentario agregado exitosamente'
        ]);
    }

    public function getComments($id)
    {
        $task = Task::find($id);
        // Verificamos si la tarea existe
        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }

        // Obtenemos los comentarios
        $comments = $task->comments()->with('user')->get();

        if ($comments->isEmpty()) {
            return response()->json([
                'message' => 'No hay comentarios para esta tarea'
            ]);
        }

        return response()->json([
            'comments' => $comments
        ]);
    }

    public function timeLog(Request $request, $id)
    {
        $task = Task::find($id);
        // Verificamos si la tarea existe
        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }

        // Verifica si el usuario tiene permiso para registrar tiempo
        $this->authorize('timeLog', $task);

        // Validamos datos
        $request->validate([
            'minutes' => 'required|integer|min:1',
        ]);

        // Agregamos el tiempo
        $task->timeLogs()->create([
            'user_id' => Auth::id(),
            'minutes' => $request->minutes,
        ]);

        // Actualizamos el tiempo total gastado
        $task->update([
            'total_time_spent' => $task->total_time_spent + $request->minutes,
            'status' => 'in_progress',
        ]);

        return response()->json([
            'message' => 'Tiempo registrado exitosamente'
        ]);
    }

    public function getTimeLogs($id)
    {
        $task = Task::find($id);
        // Verificamos si la tarea existe
        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }

        // Obtenemos los registros de tiempo
        $timeLogs = $task->timeLogs()->with('user')->get();

        if ($timeLogs->isEmpty()) {
            return response()->json([
                'message' => 'No hay registros de tiempo para esta tarea'
            ]);
        }

        return response()->json([
            'time_logs' => $timeLogs
        ]);
    }

    public function uploadFile(Request $request, $id)
    {
        $task = Task::find($id);
        // Verificamos si la tarea existe
        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }

        // Verifica si el usuario tiene permiso para subir archivos
        $this->authorize('uploadFile', $task);

        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:4048', // Ajusta los tipos y tamaños según tus necesidades
        ]);
        // Obtener el archivo del request
        $file = $request->file('file');

        $fileName = time().'_'.$file->getClientOriginalName();

        $filePath = "tasks/{$id}/{$fileName}";

        // Agregamos el archivo
        $task->files()->create([
            'user_id' => Auth::id(),
            'task_id' => $id,
            'file_path' => $filePath,
        ]);

        // Almacenamos el archivo en el disco 'public'
        $file->storeAs($filePath, '', 'public');

        return response()->json([
            'message' => 'Archivo subido exitosamente',
            'file_path' => $filePath
        ]);
    }

    public function getFiles($id)
    {
        $task = Task::find($id);
        // Verificamos si la tarea existe
        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }

        // Obtenemos los archivos
        $files = $task->files()->with('user')->get();

        if ($files->isEmpty()) {
            return response()->json([
                'message' => 'No hay archivos para esta tarea'
            ]);
        }

        return response()->json([
            'files' => $files
        ]);
    }   

    public function finish($id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'Mensaje' => 'Tarea no encontrada'
            ], 404);
        }

        // Verificamos si el usuario tiene permiso para finalizar
        $this->authorize('finish', $task);
        
        // Cambiar el estado de la tarea a 'completed'
        $task->update([
            'status' => 'completed'
        ]);

        return response()->json([
            'message' => 'Tarea finalizada exitosamente',
        ]);
        
    }

}
