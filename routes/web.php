<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// Route Default
Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tasks
    Route::get('/tasks/{id}', [TaskController::class, 'tasks'])->name('tasks');
    Route::post('/task-check', [TaskController::class, 'toggleCheck'])->name('task.check');
    Route::get('/user-tasks/{id}', [TaskController::class, 'editUserTasks'])->name('tasks.edit');
    Route::post('/user-tasks/{id}/store', [TaskController::class, 'storeUserTasks'])->name('tasks.store');
    Route::delete('/user-tasks/{task}/delete', [TaskController::class, 'deleteUserTask'])->name('tasks.delete');
    Route::put('/user-tasks/{task}/update', [TaskController::class, 'updateUserTask'])->name('tasks.update');

    // Task Statistik
    Route::get('/statistik/{user_id}', [TaskController::class, 'statistik'])->name('tasks.statistik');
    Route::get('/statistik/{year}/{month}/{user_id}', [TaskController::class, 'statistikdata'])->name('tasks.statistikdata');
});

require __DIR__.'/auth.php';