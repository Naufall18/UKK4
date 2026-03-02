<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\AnggotaController;
use App\Http\Controllers\Api\OtpController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/send-otp', [OtpController::class, 'sendOtp']);
Route::post('/verify-otp', [OtpController::class, 'verifyOtp']);

// Proxied image route to bypass Flutter Web CORS over artisan serve
Route::get('/cover/{path}', function ($path) {
    $fullPath = 'covers/' . $path;
    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($fullPath)) {
        abort(404);
    }
    return response()->file(storage_path('app/public/' . $fullPath));
})->where('path', '.*');

// Protected Routes (Butuh Token)
Route::middleware('auth:sanctum')->group(function () {

    // Auth & Profile
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);

    // Buku (Bisa diakses Admin & Siswa untuk GET)
    Route::get('/buku', [BukuController::class, 'index']);
    Route::get('/buku/{id}', [BukuController::class, 'show']);

    // CRUD Buku (Khusus Admin) - Note: Update pakai POST dengan _method=PUT untuk support FormData (Image)
    Route::post('/buku', [BukuController::class, 'store']);
    Route::post('/buku/{id}', [BukuController::class, 'update']);
    Route::delete('/buku/{id}', [BukuController::class, 'destroy']);

    // Transaksi Khusus Siswa
    Route::get('/siswa/dashboard', [DashboardController::class, 'siswaStats']);
    Route::get('/siswa/riwayat', [TransaksiController::class, 'siswaRiwayat']);
    Route::post('/siswa/pinjam', [TransaksiController::class, 'pinjam']);
    Route::post('/siswa/transaksi/{id}/kembalikan', [TransaksiController::class, 'siswaKembalikan']);

    // Admin Endpoints
    Route::get('/admin/dashboard', [DashboardController::class, 'adminStats']);
    Route::get('/admin/transaksi', [TransaksiController::class, 'adminTransaksi']);
    Route::post('/admin/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan']);

    // CRUD Anggota (Khusus Admin)
    Route::get('/admin/anggota', [AnggotaController::class, 'index']);
    Route::post('/admin/anggota', [AnggotaController::class, 'store']);
    Route::put('/admin/anggota/{id}', [AnggotaController::class, 'update']);
    Route::delete('/admin/anggota/{id}', [AnggotaController::class, 'destroy']);

});
