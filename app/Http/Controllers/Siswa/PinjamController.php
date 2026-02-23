<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PinjamController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::where('stok', '>', 0);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $bukus = $query->orderBy('judul')->paginate(10);

        return view('siswa.pinjam', compact('bukus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
        ]);

        $userId = Auth::id();
        $bukuId = $request->buku_id;

        // Cek apakah siswa sudah meminjam buku yang sama dan belum dikembalikan
        $sudahPinjam = Peminjaman::where('user_id', $userId)
            ->where('buku_id', $bukuId)
            ->where('status', 'dipinjam')
            ->exists();

        if ($sudahPinjam) {
            return back()->with('error', 'Anda masih meminjam buku ini. Kembalikan terlebih dahulu.');
        }

        $buku = Buku::findOrFail($bukuId);

        if ($buku->stok <= 0) {
            return back()->with('error', 'Stok buku habis.');
        }

        Peminjaman::create([
            'user_id' => $userId,
            'buku_id' => $bukuId,
            'tgl_pinjam' => Carbon::today(),
            'tgl_kembali_rencana' => Carbon::today()->addDays(7),
            'status' => 'dipinjam',
        ]);

        $buku->decrement('stok');

        return back()->with('success', 'Buku "' . $buku->judul . '" berhasil dipinjam. Batas pengembalian: ' . Carbon::today()->addDays(7)->format('d/m/Y'));
    }
}
