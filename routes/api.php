<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\TransaksiController;
use App\Http\Controllers\Api\AnggotaController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\PengaturanController;

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
Route::post('/support-request', [AuthController::class, 'requestSupport']);
Route::post('/send-reset-otp', [AuthController::class, 'sendResetOtp']);
Route::post('/verify-reset-otp', [AuthController::class, 'verifyResetOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

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
    Route::post('/siswa/transaksi/{id}/confirm-pickup', [TransaksiController::class, 'confirmPickupStudent']);
    Route::post('/siswa/transaksi/{id}/item/{itemId}/kembalikan', [TransaksiController::class, 'returnTransactionItem']);
    Route::post('/siswa/transaksi/{id}/kembalikan', [TransaksiController::class, 'siswaKembalikan']);

    // Admin Endpoints
    Route::get('/admin/dashboard', [DashboardController::class, 'adminStats']);
    Route::get('/admin/transaksi', [TransaksiController::class, 'adminTransaksi']);
    Route::post('/admin/transaksi/{id}/approve', [TransaksiController::class, 'approve']);
    Route::post('/admin/transaksi/{id}/confirm-pickup', [TransaksiController::class, 'confirmPickupAdmin']);
    Route::post('/admin/transaksi/{id}/reject', [TransaksiController::class, 'reject']);
    Route::post('/admin/transaksi/{id}/mark-taken', [TransaksiController::class, 'markAsTaken']);
    Route::post('/admin/transaksi/{id}/kembalikan', [TransaksiController::class, 'kembalikan']);
    Route::post('/admin/transaksi/{id}/bayar-denda', [TransaksiController::class, 'bayarDenda']);
    Route::delete('/admin/transaksi/{id}', [TransaksiController::class, 'destroy']);

    // CRUD Anggota (Khusus Admin)
    Route::get('/admin/anggota', [AnggotaController::class, 'index']);
    Route::post('/admin/anggota', [AnggotaController::class, 'store']);
    Route::put('/admin/anggota/{id}', [AnggotaController::class, 'update']);
    Route::post('/admin/anggota/{id}/toggle-status', [AnggotaController::class, 'toggleStatus']);
    Route::delete('/admin/anggota/{id}', [AnggotaController::class, 'destroy']);

    // Pengaturan (Khusus Admin)
    Route::get('/admin/pengaturan', [PengaturanController::class, 'index']);
    Route::post('/admin/pengaturan', [PengaturanController::class, 'update']);

});
