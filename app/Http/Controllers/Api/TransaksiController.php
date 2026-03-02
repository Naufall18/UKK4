<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    // Mengambil riwayat transaksi user yang login (Siswa)
    public function siswaRiwayat()
    {
        $userId = Auth::id();
        $riwayat = Peminjaman::with('buku')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Transform data untuk memudahkan Flutter menerima URL cover
        $riwayat->transform(function ($item) {
            if ($item->buku) {
                $item->buku->cover_url = $item->buku->cover ? asset('storage/' . $item->buku->cover) : null;
            }
            return $item;
        });

        return response()->json([
            'success' => true,
            'message' => 'Riwayat transaksi berhasil diambil',
            'data' => $riwayat
        ], 200);
    }

    // Siswa meminjam buku
    public function pinjam(Request $request)
    {
        $request->validate(['buku_id' => 'required|exists:bukus,id']);

        $userId = Auth::id();
        $bukuId = $request->buku_id;

        // Cek apakah sudah meminjam buku yang sama
        $sudahPinjam = Peminjaman::where('user_id', $userId)
            ->where('buku_id', $bukuId)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return response()->json([
                'success' => false,
                'message' => 'Anda masih meminjam buku ini.'
            ], 400);
        }

        $buku = Buku::findOrFail($bukuId);

        if ($buku->stok <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'Stok buku habis.'
            ], 400);
        }

        // Buat peminjaman
        $peminjaman = Peminjaman::create([
            'user_id' => $userId,
            'buku_id' => $bukuId,
            'tgl_pinjam' => Carbon::today(),
            'tgl_kembali_rencana' => Carbon::today()->addDays(7),
            'status' => 'dipinjam',
        ]);

        $buku->decrement('stok');

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dipinjam',
            'data' => $peminjaman
        ], 201);
    }

    // Siswa mengembalikan buku
    public function siswaKembalikan($id)
    {
        $userId = Auth::id();
        $peminjaman = Peminjaman::where('id', $id)->where('user_id', $userId)->first();

        if (!$peminjaman) {
            return response()->json(['message' => 'Transaksi tidak ditemukan atau bukan milik Anda'], 404);
        }

        if ($peminjaman->status !== 'dipinjam') {
            return response()->json(['message' => 'Buku sudah dikembalikan'], 400);
        }

        $today = Carbon::today();
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

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dikembalikan',
            'denda' => $denda,
            'data' => $peminjaman
        ], 200);
    }

    // Mengambil semua transaksi (Admin)
    public function adminTransaksi()
    {
        // Pastikan hanya admin yang bisa akses middleware
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $transaksi = Peminjaman::with(['user', 'buku'])->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $transaksi
        ], 200);
    }

    // Admin mengembalikan buku
    public function kembalikan($id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $peminjaman = Peminjaman::find($id);

        if (!$peminjaman) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        if ($peminjaman->status !== 'dipinjam') {
            return response()->json(['message' => 'Buku sudah dikembalikan'], 400);
        }

        $today = Carbon::today();
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

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dikembalikan',
            'denda' => $denda,
            'data' => $peminjaman
        ], 200);
    }
}
