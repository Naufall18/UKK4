<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayats = Peminjaman::with('buku')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('siswa.riwayat', compact('riwayats'));
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan sebelumnya.');
        }

        $today = \Carbon\Carbon::today();
        $denda = 0;

        if ($today->gt($peminjaman->tgl_kembali_rencana)) {
            $hariTerlambat = $today->diffInDays($peminjaman->tgl_kembali_rencana);
            $denda = $hariTerlambat * 1000;
        }

        $peminjaman->update([
            'tgl_kembali_aktual' => $today,
            'status' => 'dikembalikan',
            'denda' => $denda,
        ]);

        $peminjaman->buku->increment('stok');

        $msg = 'Buku berhasil dikembalikan.';
        if ($denda > 0) {
            $msg .= ' Anda terkena denda sebesar Rp ' . number_format($denda, 0, ',', '.');
        }

        return back()->with('success', $msg);
    }
}
