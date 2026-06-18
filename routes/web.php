<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:wali_kelas'])->group(function () {
    Route::get('/wali-kelas/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('wali-kelas.dashboard');
});

Route::middleware(['auth', 'role:tata_usaha'])->group(function () {
    Route::get('/tata-usaha/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('tata-usaha.dashboard');
    
    Route::resource('kriteria', \App\Http\Controllers\KriteriaController::class)->only(['index', 'show', 'edit', 'update']);
    Route::resource('sub_kriteria', \App\Http\Controllers\SubKriteriaController::class)->only(['index', 'show', 'edit', 'update']);
});

Route::middleware(['auth', 'role:kepala_sekolah'])->group(function () {
    Route::get('/kepala-sekolah/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('kepala-sekolah.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::resource('siswas', \App\Http\Controllers\SiswaController::class);
    
    Route::get('/topsis', [\App\Http\Controllers\TopsisController::class, 'index'])->name('topsis.index');
    Route::post('/topsis/calculate', [\App\Http\Controllers\TopsisController::class, 'calculate'])->name('topsis.calculate');
    Route::get('/topsis/transparansi', [\App\Http\Controllers\TopsisController::class, 'transparansi'])->name('topsis.transparansi');

    Route::get('/pengaturan', [\App\Http\Controllers\PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [\App\Http\Controllers\PengaturanController::class, 'update'])->name('pengaturan.update');

    Route::get('/laporan/pdf', [\App\Http\Controllers\LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [\App\Http\Controllers\LaporanController::class, 'exportExcel'])->name('laporan.excel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
