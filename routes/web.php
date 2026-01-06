<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TeacherCalendarController;
use Illuminate\Support\Facades\Route;

// Route Default
Route::get('/', function () {
    return redirect('/dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Tasks
    Route::get('/my-tasks/{id}', [TaskController::class, 'myTasks'])->name('my-tasks');
    Route::get('/tasks/{id}', [TaskController::class, 'tasks'])->name('tasks');
    Route::post('/task-check', [TaskController::class, 'toggleCheck'])->name('task.check');
    Route::get('/user-tasks/{id}', [TaskController::class, 'editUserTasks'])->name('tasks.edit');
    Route::post('/user-tasks/{id}/store', [TaskController::class, 'storeUserTasks'])->name('tasks.store');
    Route::delete('/user-tasks/{task}/delete', [TaskController::class, 'deleteUserTask'])->name('tasks.delete');
    Route::put('/user-tasks/{task}/update', [TaskController::class, 'updateUserTask'])->name('tasks.update');
    Route::get('/teacher-planner/{id}', [TaskController::class, 'teacherPlanner'])->name('tasks.planner');
    Route::get('/teacher-project/{id}', [TaskController::class, 'teacherProject'])->name('tasks.project');
    Route::post('/task-skip', [TaskController::class, 'toggleSkip'])->name('task.skip');

    // Teacher Calendar & Notes
    Route::get('/teacher-calendar/{id}', [TeacherCalendarController::class, 'index'])->name('teacher.calendar');
    Route::get('/teacher-note/all', [TeacherCalendarController::class, 'getAllNotes'])->name('teacher.note.all');
    Route::post('/teacher-note/save', [TeacherCalendarController::class, 'saveNote'])->name('teacher.note.save');
    Route::get('/teacher-note/get', [TeacherCalendarController::class, 'getNote'])->name('teacher.note.get');

    // Laporan Bulanan
    Route::get('/semua-laporan', [LaporanController::class, 'laporanall'])->name('laporanall');
    Route::post('/laporan', [LaporanController::class, 'storeOrUpdate'])->name('laporan.store');
    Route::get('/nilai-laporan/{id}', [LaporanController::class, 'nilailapor'])->name('nilailapor');
    Route::post('/nilai-laporan/{id}', [LaporanController::class, 'storenilai'])->name('laporan.nilai');
    Route::delete('/hapus-laporan/{id}', [LaporanController::class, 'hapuslaporan'])->name('laporan.delete');

    // Task Statistik
    Route::get('/statistik/{user_id}', [TaskController::class, 'statistik'])->name('tasks.statistik');
    Route::get('/statistik/{year}/{month}/{user_id}', [TaskController::class, 'statistikdata'])->name('tasks.statistikdata');
});

require __DIR__ . '/auth.php';