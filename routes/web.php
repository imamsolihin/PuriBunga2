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
    Route::post('/iuran/{iuran}/toggle-status', [\App\Http\Controllers\Admin\IuranController::class, 'toggleStatus'])->name('iuran.toggle-status');
    Route::resource('kategori-iuran', \App\Http\Controllers\Admin\KategoriIuranController::class);
    Route::resource('coa', \App\Http\Controllers\Admin\CoaController::class);
    Route::resource('jurnal', \App\Http\Controllers\Admin\JurnalController::class);
    Route::resource('kas-kecil', \App\Http\Controllers\Admin\KasKecilController::class);
    Route::resource('pengeluaran', \App\Http\Controllers\Admin\PengeluaranController::class);
    Route::resource('pengumuman', \App\Http\Controllers\Admin\PengumumanController::class);

    // Laporan
    Route::get('/laporan', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/buku-besar', [\App\Http\Controllers\Admin\ReportController::class, 'bukuBesar'])->name('laporan.buku-besar');
    Route::get('/laporan/neraca-saldo', [\App\Http\Controllers\Admin\ReportController::class, 'neracaSaldo'])->name('laporan.neraca-saldo');
    Route::get('/laporan/kas', [\App\Http\Controllers\Admin\ReportController::class, 'laporanKas'])->name('laporan.kas');
    Route::get('/laporan/neraca-ytd', [\App\Http\Controllers\Admin\ReportController::class, 'neracaYtd'])->name('laporan.neraca-ytd');
});

// ─── User Routes ──────────────────────────────────────────────────────────────
Route::prefix('user')->name('user.')->middleware(['auth', 'role:user'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/penghuni', [\App\Http\Controllers\User\DashboardController::class, 'storePenghuni'])->name('dashboard.store-penghuni');
    Route::get('/iuran', [\App\Http\Controllers\User\IuranController::class, 'index'])->name('iuran');
    Route::get('/pengumuman', [\App\Http\Controllers\User\PengumumanController::class, 'index'])->name('pengumuman');
});
