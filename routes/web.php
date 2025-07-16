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

    // Riwayat
    Route::get('/riwayat', [PageController::class, 'riwayat'])->name('riwayat');

    // Scan QR
    Route::get('/scan-code', [PageController::class, 'scanqr'])->name('scanqr');
    Route::post('/scan-code', [PageController::class, 'searchdata'])->name('searchdata');
    Route::get('/detail-unit/{zona_id}', [PageController::class, 'detailunit'])->name('detailunit');
});

require __DIR__.'/auth.php';