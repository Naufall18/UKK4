<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuku = Buku::count();
        $totalAnggota = User::where('role', 'siswa')->count();
        $peminjamanAktif = Peminjaman::where('status', 'dipinjam')->count();
        $pengembalianHariIni = Peminjaman::where('status', 'dikembalikan')
            ->whereDate('tgl_kembali_aktual', Carbon::today())
            ->count();

        $peminjamanTerbaru = Peminjaman::with(['user', 'buku'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalAnggota',
            'peminjamanAktif',
            'pengembalianHariIni',
            'peminjamanTerbaru'
        ));
    }
}
