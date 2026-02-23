<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $bukuDipinjam = Peminjaman::where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->count();

        $totalPeminjaman = Peminjaman::where('user_id', $userId)->count();

        $totalDenda = Peminjaman::where('user_id', $userId)
            ->sum('denda');

        $peminjamanAktif = Peminjaman::with('buku')
            ->where('user_id', $userId)
            ->where('status', 'dipinjam')
            ->orderBy('tgl_kembali_rencana')
            ->get();

        return view('siswa.dashboard', compact(
            'bukuDipinjam',
            'totalPeminjaman',
            'totalDenda',
            'peminjamanAktif'
        ));
    }
}
