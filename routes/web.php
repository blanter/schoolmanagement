<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PageController;
use Illuminate\Support\Facades\Route;

// Route Default
Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Route Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tasks
    Route::get('/tasks/{id}', [PageController::class, 'tasks'])->name('tasks');
    Route::post('/task-check', [PageController::class, 'toggleCheck'])->name('task.check');

    // Tasks CRUD
    Route::get('/user-tasks/{id}', [PageController::class, 'editUserTasks'])->name('tasks.edit');
    Route::post('/user-tasks/{id}/store', [PageController::class, 'storeUserTasks'])->name('tasks.store');
    Route::delete('/user-tasks/{task}/delete', [PageController::class, 'deleteUserTask'])->name('tasks.delete');
    Route::put('/user-tasks/{task}/update', [PageController::class, 'updateUserTask'])->name('tasks.update');

});

require __DIR__.'/auth.php';