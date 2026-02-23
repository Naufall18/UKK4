<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $query = Peminjaman::with(['user', 'buku']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%");
            });
        }

        $transaksis = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.transaksi.index', compact('transaksis'));
    }

    public function kembalikan($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);

        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku sudah dikembalikan.');
        }

        $today = Carbon::today();
        $denda = 0;

        if ($today->gt($peminjaman->tgl_kembali_rencana)) {
            $hariTerlambat = $today->diffInDays($peminjaman->tgl_kembali_rencana);
            $denda = $hariTerlambat * 1000; // Rp 1.000 per hari
        }

        $peminjaman->update([
            'tgl_kembali_aktual' => $today,
            'status' => 'dikembalikan',
            'denda' => $denda,
        ]);

        // Update stok buku +1
        $peminjaman->buku->increment('stok');

        $message = 'Buku berhasil dikembalikan.';
        if ($denda > 0) {
            $message .= ' Denda keterlambatan: Rp ' . number_format($denda, 0, ',', '.');
        }

        return back()->with('success', $message);
    }
}
