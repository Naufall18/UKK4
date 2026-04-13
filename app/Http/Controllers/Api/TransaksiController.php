<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Peminjaman;
use App\Models\Buku;
use App\Models\Pengaturan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransaksiController extends Controller
{
    private function processBookCover($buku)
    {
        if ($buku) {
            $buku->cover_url = $buku->cover ? (str_starts_with($buku->cover, 'http') || str_starts_with($buku->cover, 'assets/') ? $buku->cover : asset('storage/' . $buku->cover)) : null;
        }
        return $buku;
    }

    private function groupTransactions($transaksi)
    {
        $grouped = [];
        foreach ($transaksi as $t) {
            $kode = $t->kode_transaksi ?: 'single_' . $t->id;

            // Format single cover just in case
            if ($t->buku) {
                $t->buku = $this->processBookCover($t->buku);
            }

            if (!isset($grouped[$kode])) {
                $grouped[$kode] = $t->toArray();
                $grouped[$kode]['items'] = [];
            }

            $itemData = [
                'id' => $t->id,
                'buku_id' => $t->buku_id,
                'quantity' => $t->jumlah ?? 1,
                'durasi_hari' => $t->durasi_hari,
                'kondisi_buku' => $t->kondisi_buku,
                'catatan_kondisi' => $t->catatan_kondisi,
                'status_bayar_denda' => $t->status_bayar_denda,
                'status' => $t->status,
                'buku' => $t->buku ? $t->buku->toArray() : null,
                'item_id' => $t->id,
                'tgl_kembali_rencana' => $t->tgl_kembali_rencana,
                'tgl_kembali_aktual' => $t->tgl_kembali_aktual,
                'denda' => $t->denda ?? 0,
            ];

            $grouped[$kode]['items'][] = $itemData;
        }

        // Calculate aggregate status, denda, and dates for each group
        foreach ($grouped as $kode => &$data) {
            $statuses = array_column($data['items'], 'status');
            $totalDenda = array_sum(array_column($data['items'], 'denda'));
            $data['denda'] = $totalDenda;

            // Priority: pending > approved > diambil > dipinjam > terlambat (if ongoing) > dikembalikan
            if (in_array('pending', $statuses)) {
                $data['status'] = 'pending';
                $data['tgl_kembali_aktual'] = null;
            } elseif (in_array('approved', $statuses)) {
                $data['status'] = 'approved';
                $data['tgl_kembali_aktual'] = null;
            } elseif (in_array('diambil', $statuses)) {
                $data['status'] = 'diambil';
                $data['tgl_kembali_aktual'] = null;
            } elseif (in_array('dipinjam', $statuses)) {
                $data['status'] = 'dipinjam';
                $data['tgl_kembali_aktual'] = null;
            } else {
                // All items are finished (dikembalikan, terlambat, or rejected)
                if (in_array('terlambat', $statuses)) {
                    $data['status'] = 'terlambat';
                } elseif (in_array('dikembalikan', $statuses)) {
                    $data['status'] = 'dikembalikan';
                }

                // Set return date to the latest return date among items
                $returnDates = array_filter(array_column($data['items'], 'tgl_kembali_aktual'));
                if (!empty($returnDates)) {
                    $data['tgl_kembali_aktual'] = max($returnDates);
                }
            }
        }

        return array_values($grouped);
    }

    // Mengambil riwayat transaksi user yang login (Siswa)
    public function siswaRiwayat()
    {
        $userId = Auth::id();
        $riwayat = Peminjaman::with('buku')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat transaksi berhasil diambil',
            'data' => $this->groupTransactions($riwayat)
        ], 200);
    }

    public function pinjam(Request $request)
    {
        $request->validate([
            'buku_id' => 'nullable|exists:bukus,id',
            'items' => 'nullable|array',
            'items.*.buku_id' => 'required_with:items|exists:bukus,id',
            'items.*.quantity' => 'nullable|integer|min:1',
            'items.*.durasi_hari' => 'nullable|integer|min:1|max:60',
            'durasi_hari' => 'nullable|integer|min:1|max:60',
        ]);

        $userId = Auth::id();
        $durasiHari = $request->durasi_hari ?? 7;

        $itemsToProcess = [];
        if ($request->has('items') && is_array($request->items)) {
            foreach ($request->items as $it) {
                $itemsToProcess[] = [
                    'buku_id' => $it['buku_id'],
                    'jumlah' => $it['quantity'] ?? 1,
                    'durasi_hari' => $it['durasi_hari'] ?? $durasiHari
                ];
            }
        } else if ($request->buku_id) {
            $itemsToProcess[] = [
                'buku_id' => $request->buku_id,
                'jumlah' => 1,
                'durasi_hari' => $durasiHari
            ];
        } else {
            return response()->json(['success' => false, 'message' => 'Buku harus diisi'], 400);
        }

        // Cek stok dan duplikasi
        foreach ($itemsToProcess as $item) {
            $buku = Buku::findOrFail($item['buku_id']);
            if ($buku->stok < $item['jumlah']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok buku "' . $buku->judul . '" tidak mencukupi.'
                ], 400);
            }
            // Check removed: allowed to borrow the same book multiple times
        }

        $kodeTransaksi = 'TRX-' . strtoupper(Str::random(10));
        $created = [];
        foreach ($itemsToProcess as $item) {
            $created[] = Peminjaman::create([
                'kode_transaksi' => $kodeTransaksi,
                'user_id' => $userId,
                'buku_id' => $item['buku_id'],
                'jumlah' => $item['jumlah'],
                'status_approval' => 'pending',
                'status' => 'pending',
                'durasi_hari' => $item['durasi_hari'],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman berhasil diajukan. Silakan tunggu persetujuan petugas.',
            'data' => $this->groupTransactions($created)[0] ?? null
        ], 201);
    }

    public function siswaKembalikan($id)
    {
        $userId = Auth::id();
        $p = Peminjaman::where('id', $id)->where('user_id', $userId)->first();
        if (!$p)
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);

        $allToUpdate = $p->kode_transaksi ? Peminjaman::where('kode_transaksi', $p->kode_transaksi)->get() : collect([$p]);

        $denda = 0;
        $today = Carbon::today();
        foreach ($allToUpdate as $peminjaman) {
            if ($peminjaman->status !== 'dipinjam')
                continue;

            $dendaItem = 0;
            if ($today->gt($peminjaman->tgl_kembali_rencana)) {
                $hariTerlambat = $today->diffInDays($peminjaman->tgl_kembali_rencana);
                $tarifDenda = (int) Pengaturan::getValue('denda_harian', 1000);
                $dendaItem = $hariTerlambat * $tarifDenda * $peminjaman->jumlah;
            }

            $peminjaman->update([
                'tgl_kembali_aktual' => $today,
                'status' => 'dikembalikan',
                'denda' => $dendaItem,
            ]);
            $peminjaman->buku->increment('stok', $peminjaman->jumlah);
            $denda += $dendaItem;
        }

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dikembalikan',
            'denda' => $denda
        ], 200);
    }

    public function adminTransaksi(Request $request)
    {
        if (!Auth::user() || !Auth::user()->isAdmin())
            return response()->json(['message' => 'Unauthorized'], 403);

        $query = Peminjaman::with(['user', 'buku']);

        if ($request->has('status_approval') && $request->status_approval != '') {
            $query->where('status_approval', $request->status_approval);
        }
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            })->orWhereHas('buku', function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $transaksi = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $this->groupTransactions($transaksi)
        ], 200);
    }

    public function approve(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin())
            return response()->json(['message' => 'Unauthorized'], 403);

        $p = Peminjaman::with('buku')->find($id);
        if (!$p || $p->status_approval !== 'pending')
            return response()->json(['message' => 'Pengajuan tidak valid'], 404);

        $allToUpdate = $p->kode_transaksi ? Peminjaman::where('kode_transaksi', $p->kode_transaksi)->get() : collect([$p]);

        // Cek stok semua dulu
        foreach ($allToUpdate as $item) {
            if ($item->buku->stok < $item->jumlah) {
                return response()->json(['message' => 'Stok buku ' . $item->buku->judul . ' habis'], 400);
            }
        }

        // Granular data from items array if provided
        $itemsData = $request->input('items', []);

        foreach ($allToUpdate as $item) {
            $updateData = [
                'status_approval' => 'approved',
                'tgl_pinjam' => Carbon::today(),
                'tgl_kembali_rencana' => Carbon::today()->addDays($item->durasi_hari),
            ];

            // Check if there's specific data for this item ID
            if (isset($itemsData[$item->id])) {
                $updateData['kondisi_buku'] = $itemsData[$item->id]['kondisi_buku'] ?? 'baik';
                $updateData['catatan_kondisi'] = $itemsData[$item->id]['catatan_kondisi'] ?? null;
            } else {
                // Fallback to global request data for backward compatibility
                $updateData['kondisi_buku'] = $request->input('kondisi_buku', 'baik');
                $updateData['catatan_kondisi'] = $request->input('catatan_kondisi');
            }

            $item->update($updateData);
            $item->buku->decrement('stok', $item->jumlah);
        }

        return response()->json([
            'success' => true,
            'message' => 'Peminjaman disetujui. Status: Bisa Diambil.',
        ], 200);
    }

    public function markAsTaken($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin())
            return response()->json(['message' => 'Unauthorized'], 403);

        $p = Peminjaman::find($id);
        if (!$p)
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);

        $allToUpdate = $p->kode_transaksi ? Peminjaman::where('kode_transaksi', $p->kode_transaksi)->get() : collect([$p]);

        foreach ($allToUpdate as $item) {
            if ($item->status_approval === 'approved' && $item->status === 'pending') {
                $item->update(['status' => 'dipinjam']);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Buku telah diambil oleh siswa.',
        ], 200);
    }

    public function reject($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin())
            return response()->json(['message' => 'Unauthorized'], 403);

        $p = Peminjaman::find($id);
        if (!$p || $p->status_approval !== 'pending')
            return response()->json(['message' => 'Pengajuan tidak valid'], 404);

        $allToUpdate = $p->kode_transaksi ? Peminjaman::where('kode_transaksi', $p->kode_transaksi)->get() : collect([$p]);

        foreach ($allToUpdate as $item) {
            $item->update([
                'status_approval' => 'rejected',
                'status' => 'dikembalikan',
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Peminjaman ditolak.'], 200);
    }

    public function kembalikan(Request $request, $id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin())
            return response()->json(['message' => 'Unauthorized'], 403);

        $p = Peminjaman::find($id);
        if (!$p || $p->status !== 'dipinjam')
            return response()->json(['message' => 'Transaksi tidak valid'], 400);

        $allToUpdate = $p->kode_transaksi ? Peminjaman::where('kode_transaksi', $p->kode_transaksi)->get() : collect([$p]);

        $today = Carbon::today();
        $itemsData = $request->input('items', []);

        $totalDendaResponse = 0;
        $dendaLatenessResponse = 0;
        $dendaDamageResponse = 0;

        foreach ($allToUpdate as $item) {
            // Get granular data or fallback to global
            if (isset($itemsData[$item->id])) {
                $kondisi = $itemsData[$item->id]['kondisi_buku'] ?? 'baik';
                $catatan = $itemsData[$item->id]['catatan_kondisi'] ?? null;
                $dendaKerusakanPerItem = $itemsData[$item->id]['denda_kerusakan'] ?? 0;
            } else {
                $kondisi = $request->input('kondisi_buku', 'baik');
                $catatan = $request->input('catatan_kondisi');
                $dendaKerusakanPerItem = $request->input('denda_kerusakan', 0);
            }

            if ($kondisi !== 'baik' && $dendaKerusakanPerItem == 0) {
                $dendaKerusakanPerItem = match ($kondisi) {
                    'rusak_ringan' => (int) Pengaturan::getValue('denda_rusak_ringan', 10000),
                    'rusak_berat' => (int) Pengaturan::getValue('denda_rusak_berat', 25000),
                    'hilang' => (int) Pengaturan::getValue('denda_hilang', 50000),
                    default => 0,
                };
            }

            $dendaKeterlambatan = 0;
            if ($item->tgl_kembali_rencana && $today->gt($item->tgl_kembali_rencana)) {
                $tarifDenda = (int) Pengaturan::getValue('denda_harian', 1000);
                $dendaKeterlambatan = $today->diffInDays($item->tgl_kembali_rencana) * $tarifDenda * $item->jumlah;
            }
            $damage = $dendaKerusakanPerItem * $item->jumlah;
            $totalDenda = $dendaKeterlambatan + $damage;

            $status = ($dendaKeterlambatan > 0) ? 'terlambat' : 'dikembalikan';

            $item->update([
                'tgl_kembali_aktual' => $today,
                'status' => $status,
                'denda' => $totalDenda,
                'kondisi_buku' => $kondisi,
                'catatan_kondisi' => $catatan,
                'denda_kerusakan' => $damage,
            ]);

            if ($kondisi !== 'hilang') {
                $item->buku->increment('stok', $item->jumlah);
            }

            $totalDendaResponse += $totalDenda;
            $dendaLatenessResponse += $dendaKeterlambatan;
            $dendaDamageResponse += $damage;
        }

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dikembalikan',
            'denda_keterlambatan' => $dendaLatenessResponse,
            'denda_kerusakan' => $dendaDamageResponse,
            'total_denda' => $totalDendaResponse
        ], 200);
    }

    public function destroy($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin())
            return response()->json(['message' => 'Unauthorized'], 403);

        $p = Peminjaman::find($id);
        if (!$p)
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);

        $allToUpdate = $p->kode_transaksi ? Peminjaman::where('kode_transaksi', $p->kode_transaksi)->get() : collect([$p]);
        foreach ($allToUpdate as $item) {
            $item->delete();
        }

        return response()->json(['success' => true, 'message' => 'Transaksi berhasil dihapus'], 200);
    }

    public function bayarDenda($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin())
            return response()->json(['message' => 'Unauthorized'], 403);

        $p = Peminjaman::find($id);
        if (!$p)
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);

        $allToUpdate = $p->kode_transaksi ? Peminjaman::where('kode_transaksi', $p->kode_transaksi)->get() : collect([$p]);
        foreach ($allToUpdate as $item) {
            $item->update(['status_bayar_denda' => 'lunas']);
        }

        return response()->json(['success' => true, 'message' => 'Denda berhasil ditandai sebagai Lunas'], 200);
    }

    public function confirmPickupAdmin($id)
    {
        if (!Auth::user() || !Auth::user()->isAdmin())
            return response()->json(['message' => 'Unauthorized'], 403);

        $p = Peminjaman::find($id);
        if (!$p)
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);

        if ($p->status_approval !== 'approved')
            return response()->json(['message' => 'Transaksi belum disetujui'], 400);

        $allToUpdate = $p->kode_transaksi ? Peminjaman::where('kode_transaksi', $p->kode_transaksi)->get() : collect([$p]);

        foreach ($allToUpdate as $item) {
            $item->update(['status' => 'diambil']);
        }

        return response()->json(['success' => true, 'message' => 'Pengambilan dikonfirmasi'], 200);
    }

    public function confirmPickupStudent($id)
    {
        $user = Auth::user();
        $p = Peminjaman::find($id);
        if (!$p)
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);

        if ($p->user_id !== $user->id)
            return response()->json(['message' => 'Unauthorized'], 403);

        if ($p->status !== 'diambil' || $p->status_approval !== 'approved')
            return response()->json(['message' => 'Status tidak valid untuk konfirmasi'], 400);

        $allToUpdate = $p->kode_transaksi ? Peminjaman::where('kode_transaksi', $p->kode_transaksi)->get() : collect([$p]);

        foreach ($allToUpdate as $item) {
            $item->update(['status' => 'dipinjam']);
        }

        return response()->json(['success' => true, 'message' => 'Buku berhasil diambil'], 200);
    }

    public function returnTransactionItem(Request $request, $id, $itemId)
    {
        $user = Auth::user();
        $item = Peminjaman::find($itemId);
        if (!$item)
            return response()->json(['message' => 'Item tidak ditemukan'], 404);

        if ($item->user_id !== $user->id)
            return response()->json(['message' => 'Unauthorized'], 403);

        if ($item->status !== 'dipinjam')
            return response()->json(['message' => 'Status tidak valid untuk pengembalian'], 400);

        $today = Carbon::today();
        $kondisi = $request->input('kondisi_buku', 'baik');
        $catatan = $request->input('catatan_kondisi');
        $dendaKerusakan = $request->input('denda_kerusakan', 0);

        if ($kondisi !== 'baik' && $dendaKerusakan == 0) {
            $dendaKerusakan = match ($kondisi) {
                'rusak_ringan' => (int) Pengaturan::getValue('denda_rusak_ringan', 10000),
                'rusak_berat' => (int) Pengaturan::getValue('denda_rusak_berat', 25000),
                'hilang' => (int) Pengaturan::getValue('denda_hilang', 50000),
                default => 0,
            };
        }

        $dendaKeterlambatan = 0;
        if ($item->tgl_kembali_rencana && $today->gt($item->tgl_kembali_rencana)) {
            $tarifDenda = (int) Pengaturan::getValue('denda_harian', 1000);
            $dendaKeterlambatan = $today->diffInDays($item->tgl_kembali_rencana) * $tarifDenda * $item->jumlah;
        }

        $damage = $dendaKerusakan * $item->jumlah;
        $totalDenda = $dendaKeterlambatan + $damage;
        $status = ($dendaKeterlambatan > 0) ? 'terlambat' : 'dikembalikan';

        $item->update([
            'tgl_kembali_aktual' => $today,
            'status' => $status,
            'denda' => $totalDenda,
            'kondisi_buku' => $kondisi,
            'catatan_kondisi' => $catatan,
            'denda_kerusakan' => $damage,
        ]);

        if ($kondisi !== 'hilang') {
            $item->buku->increment('stok', $item->jumlah);
        }

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dikembalikan',
            'denda' => $totalDenda,
        ], 200);
    }
}
