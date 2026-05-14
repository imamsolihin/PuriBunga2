<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

// ─── Public ──────────────────────────────────────────────────────────────────
Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('home');

// ─── Auth (Breeze) ────────────────────────────────────────────────────────────
require __DIR__.'/auth.php';

// ─── Redirect setelah login berdasarkan role ─────────────────────────────────
Route::middleware('auth')->get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('user.dashboard');
})->name('dashboard');

// ─── Admin Routes ─────────────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

    Route::resource('warga', \App\Http\Controllers\Admin\WargaController::class);
    Route::resource('iuran', \App\Http\Controllers\Admin\IuranController::class);
    Route::resource('kategori-iuran', \App\Http\Controllers\Admin\KategoriIuranController::class);
    Route::resource('coa', \App\Http\Controllers\Admin\CoaController::class);
    Route::resource('jurnal', \App\Http\Controllers\Admin\JurnalController::class);
    Route::resource('kas-kecil', \App\Http\Controllers\Admin\KasKecilController::class);
    Route::resource('pengumuman', \App\Http\Controllers\Admin\PengumumanController::class);

    // Laporan
    Route::get('/laporan', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/buku-besar', [\App\Http\Controllers\Admin\ReportController::class, 'bukuBesar'])->name('laporan.buku-besar');
    Route::get('/laporan/neraca-saldo', [\App\Http\Controllers\Admin\ReportController::class, 'neracaSaldo'])->name('laporan.neraca-saldo');
});

// ─── User Routes ──────────────────────────────────────────────────────────────
Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/iuran', [\App\Http\Controllers\User\IuranController::class, 'index'])->name('iuran');
    Route::get('/pengumuman', [\App\Http\Controllers\User\PengumumanController::class, 'index'])->name('pengumuman');
});
