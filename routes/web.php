<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PenilaianSiswaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\SiswaImportController;
use App\Http\Controllers\SubKriteriaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\TopsisController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'role:wali_kelas'])->group(function () {
    Route::get('/wali-kelas/dashboard', [DashboardController::class, 'index'])->name('wali-kelas.dashboard');

    // Import Data Siswa (Wali Kelas)
    Route::get('/siswa/import', [SiswaImportController::class, 'index'])->name('siswa-import.index');
    Route::post('/siswa/import/sheets', [SiswaImportController::class, 'getSheets'])->name('siswa-import.get-sheets');
    Route::post('/siswa/import/preview', [SiswaImportController::class, 'preview'])->name('siswa-import.preview');
    Route::post('/siswa/import/process', [SiswaImportController::class, 'process'])->name('siswa-import.process');
    Route::get('/siswa/import/template/{type}', [SiswaImportController::class, 'downloadTemplate'])->name('siswa-import.template');
});

Route::middleware(['auth', 'role:kepala_sekolah'])->group(function () {
    Route::get('/kepala-sekolah/dashboard', [DashboardController::class, 'index'])->name('kepala-sekolah.dashboard');

    // Master Kelas
    Route::resource('kelas', KelasController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['kelas' => 'kelas']);

    // Master Kriteria & Sub-Kriteria
    Route::resource('kriteria', KriteriaController::class)->only(['index', 'store', 'update', 'destroy'])->parameters(['kriteria' => 'kriteria']);
    Route::post('kriteria/{kriteria}/sub-kriteria', [SubKriteriaController::class, 'store'])->name('sub_kriteria.store');
    Route::put('sub-kriteria/{subKriteria}', [SubKriteriaController::class, 'update'])->name('sub_kriteria.update');
    Route::delete('sub-kriteria/{subKriteria}', [SubKriteriaController::class, 'destroy'])->name('sub_kriteria.destroy');

    // Kelola User
    Route::resource('users', UserController::class)->except(['show', 'destroy']);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');

    // Perhitungan TOPSIS (Khusus Kepala Sekolah)
    Route::get('/topsis', [TopsisController::class, 'index'])->name('topsis.index');
    Route::post('/topsis/calculate', [TopsisController::class, 'calculate'])->name('topsis.calculate');
    Route::get('/topsis/transparansi', [TopsisController::class, 'transparansi'])->name('topsis.transparansi');
});

Route::middleware('auth')->group(function () {
    Route::resource('tahun-ajaran', TahunAjaranController::class)->only(['index', 'store', 'destroy'])->parameters(['tahun-ajaran' => 'tahunAjaran']);
    Route::resource('siswas', SiswaController::class);

    // Penilaian Siswa Routes (Index & Edit)
    Route::get('/penilaian-siswa', [PenilaianSiswaController::class, 'index'])->name('penilaian-siswa.index');
    Route::get('/penilaian-siswa/{siswa}/edit', [PenilaianSiswaController::class, 'edit'])->name('penilaian-siswa.edit');
    Route::put('/penilaian-siswa/{siswa}', [PenilaianSiswaController::class, 'update'])->name('penilaian-siswa.update');
    // Move topsis routes to kepala sekolah block
    Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan', [PengaturanController::class, 'update'])->name('pengaturan.update');

    Route::get('/laporan/pdf', [LaporanController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [LaporanController::class, 'exportExcel'])->name('laporan.excel');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
