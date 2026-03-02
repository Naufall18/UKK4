<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\BukuController;
use App\Http\Controllers\Admin\AnggotaController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\PinjamController;
use App\Http\Controllers\Siswa\RiwayatController;
use Illuminate\Support\Facades\Route;

// Welcome / Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Auth Routes (Guest only for login/register)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

Route::get('/home', function () {
    if (auth()->user()->role == 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('siswa.dashboard');
})->middleware('auth');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'isAdmin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('/buku', BukuController::class);
    Route::resource('/anggota', AnggotaController::class);
    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan'])->name('transaksi.kembalikan');
});

// Siswa Routes
Route::prefix('siswa')->middleware(['auth', 'isSiswa'])->name('siswa.')->group(function () {
    Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');
    Route::get('/pinjam', [PinjamController::class, 'index'])->name('pinjam.index');
    Route::post('/pinjam', [PinjamController::class, 'store'])->name('pinjam.store');
    Route::get('/riwayat', [RiwayatController::class, 'index'])->name('riwayat.index');
    Route::post('/riwayat/{id}/kembalikan', [RiwayatController::class, 'kembalikan'])->name('riwayat.kembalikan');
});
