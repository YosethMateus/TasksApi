<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas para registrar, iniciar o cerrar sesion
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// Taskas
Route::middleware('auth:sanctum')->post('/tasks', [TaskController::class, 'store']);
Route::middleware('auth:sanctum')->get('/tasks', [TaskController::class, 'index']);
Route::middleware('auth:sanctum')->get('/tasks/{id}', [TaskController::class, 'getTask']);
Route::middleware('auth:sanctum')->put('/tasks/{id}', [TaskController::class, 'update']);
Route::middleware('auth:sanctum')->delete('/tasks/{id}', [TaskController::class, 'destroy']);
Route::middleware('auth:sanctum')->patch('/tasks/{id}/restore', [TaskController::class, 'restore']);

// Comments
Route::middleware('auth:sanctum')->post('/tasks/{id}/comments', [TaskController::class, 'addComment']);
Route::middleware('auth:sanctum')->get('/tasks/{id}/comments', [TaskController::class, 'getComments']);

// Time Tracking
Route::middleware('auth:sanctum')->post('/tasks/{id}/time-log', [TaskController::class, 'timeLog']);
Route::middleware('auth:sanctum')->get('/tasks/{id}/time-log', [TaskController::class, 'getTimeLogs']);

// Files
Route::middleware('auth:sanctum')->post('/tasks/{id}/upload', [TaskController::class, 'uploadFile']);
Route::middleware('auth:sanctum')->get('/tasks/{id}/files', [TaskController::class, 'getFiles']);

