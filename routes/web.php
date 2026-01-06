<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\TeacherWeeklyPlannerController;
use App\Http\Controllers\TeacherDailyDetailController;
use App\Http\Controllers\TeacherCalendarController;
use App\Http\Controllers\TeacherStudentProgressController;
use App\Http\Controllers\TeacherMonthlyEvaluationController;
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
    Route::get('/teacher-planner-pemakmuran/{id}', [TaskController::class, 'teacherPlannerPemakmuran'])->name('tasks.planner.pemakmuran');
    Route::get('/teacher-project/{id}', [TaskController::class, 'teacherProject'])->name('tasks.project');
    Route::post('/teacher-research/save', [TaskController::class, 'saveResearchProject'])->name('teacher.research.save');
    Route::post('/teacher-video/save', [TaskController::class, 'saveVideoProject'])->name('teacher.video.save');
    Route::post('/teacher-video/delete', [TaskController::class, 'deleteVideoProject'])->name('teacher.video.delete');
    Route::post('/teacher-procurement/save', [TaskController::class, 'saveProcurement'])->name('teacher.procurement.save');
    Route::post('/teacher-procurement/delete', [TaskController::class, 'deleteProcurement'])->name('teacher.procurement.delete');
    Route::post('/task-skip', [TaskController::class, 'toggleSkip'])->name('task.skip');

    // Teacher Calendar & Notes
    Route::get('/teacher-calendar/{id}', [TeacherCalendarController::class, 'index'])->name('teacher.calendar');
    Route::get('/teacher-note/all', [TeacherCalendarController::class, 'getAllNotes'])->name('teacher.note.all');
    Route::post('/teacher-note/save', [TeacherCalendarController::class, 'saveNote'])->name('teacher.note.save');
    Route::get('/teacher-note/get', [TeacherCalendarController::class, 'getNote'])->name('teacher.note.get');

    // Teacher Weekly Planner
    Route::get('/teacher-weekly-planner/get', [TeacherWeeklyPlannerController::class, 'getPlans'])->name('teacher.weekly.get');
    Route::post('/teacher-weekly-planner/save', [TeacherWeeklyPlannerController::class, 'savePlan'])->name('teacher.weekly.save');
    Route::post('/teacher-weekly-planner/delete', [TeacherWeeklyPlannerController::class, 'deletePlan'])->name('teacher.weekly.delete');
    Route::get('/teacher-weekly-planner/{id}', [TeacherWeeklyPlannerController::class, 'index'])->name('teacher.weekly');

    // Teacher Daily Details (Monthly)
    Route::get('/teacher-daily-detail/get', [TeacherDailyDetailController::class, 'getNote'])->name('teacher.daily.get');
    Route::post('/teacher-daily-detail/save', [TeacherDailyDetailController::class, 'saveNote'])->name('teacher.daily.save');
    Route::get('/teacher-daily-detail/{id}', [TeacherDailyDetailController::class, 'index'])->name('teacher.daily');

    // Teacher Student Progress
    Route::get('/teacher-student-progress/get', [TeacherStudentProgressController::class, 'getRecords'])->name('teacher.progress.get');
    Route::post('/teacher-student-progress/save', [TeacherStudentProgressController::class, 'saveRecord'])->name('teacher.progress.save');
    Route::post('/teacher-student-progress/delete', [TeacherStudentProgressController::class, 'deleteRecord'])->name('teacher.progress.delete');
    Route::get('/teacher-student-progress/{id}', [TeacherStudentProgressController::class, 'index'])->name('teacher.progress');

    // Teacher Monthly Evaluation
    Route::get('/teacher-monthly-evaluation/get', [TeacherMonthlyEvaluationController::class, 'getData'])->name('teacher.evaluation.get');
    Route::post('/teacher-monthly-evaluation/save-guru', [TeacherMonthlyEvaluationController::class, 'saveGuru'])->name('teacher.evaluation.saveGuru');
    Route::post('/teacher-monthly-evaluation/save-nonguru', [TeacherMonthlyEvaluationController::class, 'saveNonGuru'])->name('teacher.evaluation.saveNonGuru');
    Route::post('/teacher-monthly-evaluation/delete-nonguru', [TeacherMonthlyEvaluationController::class, 'deleteNonGuru'])->name('teacher.evaluation.deleteNonGuru');
    Route::get('/teacher-monthly-evaluation/{id}', [TeacherMonthlyEvaluationController::class, 'index'])->name('teacher.evaluation');

    // Non Guru Task Note
    Route::get('/non-guru-task-note/{id}', [\App\Http\Controllers\NonGuruNoteController::class, 'index'])->name('non-guru.note');
    Route::get('/non-guru-task-note-get', [\App\Http\Controllers\NonGuruNoteController::class, 'getData'])->name('non-guru.note.get');
    Route::post('/non-guru-task-note-category-save', [\App\Http\Controllers\NonGuruNoteController::class, 'saveCategory'])->name('non-guru.note.category.save');
    Route::post('/non-guru-task-note-category-delete', [\App\Http\Controllers\NonGuruNoteController::class, 'deleteCategory'])->name('non-guru.note.category.delete');
    Route::post('/non-guru-task-note-item-save', [\App\Http\Controllers\NonGuruNoteController::class, 'saveItem'])->name('non-guru.note.item.save');
    Route::post('/non-guru-task-note-item-check', [\App\Http\Controllers\NonGuruNoteController::class, 'checkItem'])->name('non-guru.note.item.check');
    Route::post('/non-guru-task-note-item-delete', [\App\Http\Controllers\NonGuruNoteController::class, 'deleteItem'])->name('non-guru.note.item.delete');

    // Laporan Bulanan
    Route::get('/semua-laporan', [LaporanController::class, 'laporanall'])->name('laporanall');
    Route::post('/laporan', [LaporanController::class, 'storeOrUpdate'])->name('laporan.store');
    Route::get('/nilai-laporan/{id}', [LaporanController::class, 'nilailapor'])->name('nilailapor');
    Route::post('/nilai-laporan/{id}', [LaporanController::class, 'storenilai'])->name('laporan.nilai');
    Route::delete('/hapus-laporan/{id}', [LaporanController::class, 'hapuslaporan'])->name('laporan.delete');

    // Teacher Pemakmuran Detail Pages
    Route::get('/teacher-pemakmuran-detail/{id}/{type}', [\App\Http\Controllers\TeacherPemakmuranController::class, 'index'])->name('teacher.pemakmuran.detail');
    Route::get('/teacher-pemakmuran-detail/get', [\App\Http\Controllers\TeacherPemakmuranController::class, 'getContent'])->name('teacher.pemakmuran.get');
    Route::post('/teacher-pemakmuran-detail/save', [\App\Http\Controllers\TeacherPemakmuranController::class, 'saveContent'])->name('teacher.pemakmuran.save');

    // Student Lifebook
    Route::get('/student-lifebook/{id}', [\App\Http\Controllers\StudentLifebookController::class, 'index'])->name('student.lifebook');
    Route::get('/student-lifebook-get', [\App\Http\Controllers\StudentLifebookController::class, 'getData'])->name('student.lifebook.get');
    Route::post('/student-lifebook-save', [\App\Http\Controllers\StudentLifebookController::class, 'saveData'])->name('student.lifebook.save');

    // Task Statistik
    Route::get('/statistik/{user_id}', [TaskController::class, 'statistik'])->name('tasks.statistik');
    Route::get('/statistik/{year}/{month}/{user_id}', [TaskController::class, 'statistikdata'])->name('tasks.statistikdata');
});

require __DIR__ . '/auth.php';