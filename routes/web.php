<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;

// ── Авторизация ──────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
     ->middleware('auth')
     ->name('logout');

// ── Защищённые маршруты ──────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Дашборд

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    //заметки
    Route::get('/notes/create', [NoteController::class, 'create']);
    Route::post('/notes', [NoteController::class, 'store']);
    Route::get('/notes', [NoteController::class, 'index']);        // список
    Route::get('/notes/{note}', [NoteController::class, 'show']);  // просмотр
    Route::put('/notes/{note}', [NoteController::class, 'update']); // сохранение


    // Проекты
    Route::resource('projects', ProjectController::class);

    // Задачи
    Route::resource('tasks', TaskController::class);
    Route::patch('tasks/{task}/status', [TaskController::class, 'updateStatus'])
         ->name('tasks.update-status');

    // Комментарии
    Route::post('tasks/{task}/comments', [CommentController::class, 'store'])
         ->name('comments.store');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])
         ->name('comments.destroy');

    // Пользователи (только admin и manager)
    Route::resource('users', UserController::class)->except(['show']);
});
