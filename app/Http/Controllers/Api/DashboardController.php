<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function adminStats()
    {
        $user = Auth::user();
        if (!$user || !$user->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'siswa')->count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $bukuTerlambat = Peminjaman::where('status', 'dipinjam')
            ->where('tgl_kembali_rencana', '<', Carbon::today())
            ->count();
        $pengembalianHariIni = Peminjaman::where('status', 'dikembalikan')
            ->whereDate('tgl_kembali_aktual', Carbon::today())
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_buku' => $totalBuku,
                'total_anggota' => $totalAnggota,
                'peminjaman_aktif' => $peminjamanAktif,
                'buku_terlambat' => $bukuTerlambat,
                'pengembalian_hari_ini' => $pengembalianHariIni
            ]
        ], 200);
    }

    public function siswaStats()
    {
        $userId = Auth::id();

        $bukuDipinjam = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')->count();

        $totalPeminjaman = Peminjaman::where('user_id', $userId)->count();

        $totalDenda = Peminjaman::where('user_id', $userId)->sum('denda');

        return response()->json([
            'success' => true,
            'data' => [
                'buku_dipinjam' => $bukuDipinjam,
                'total_peminjaman' => $totalPeminjaman,
                'total_denda' => $totalDenda
            ]
        ], 200);
    }
}
