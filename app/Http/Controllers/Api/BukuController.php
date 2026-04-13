<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $query = Buku::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('pengarang', 'like', "%{$search}%")
                    ->orWhere('kategori', 'like', "%{$search}%");
            });
        }

        $bukus = $query->orderBy('judul')->get();

        // Add full URL to cover image
        $bukus->transform(function ($buku) {
            if ($buku->cover) {
                if (str_starts_with($buku->cover, 'http')) {
                    $buku->cover_url = $buku->cover;
                } else if (str_starts_with($buku->cover, 'assets/')) {
                    $buku->cover_url = $buku->cover;
                } else {
                    $buku->cover_url = asset('storage/' . $buku->cover);
                }
            } else {
                $buku->cover_url = null;
            }
            return $buku;
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar buku berhasil diambil',
            'data' => $bukus
        ], 200);
    }

    public function show($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json([
                'success' => false,
                'message' => 'Buku tidak ditemukan'
            ], 404);
        }

        if ($buku->cover) {
            if (str_starts_with($buku->cover, 'http')) {
                $buku->cover_url = $buku->cover;
            } else if (str_starts_with($buku->cover, 'assets/')) {
                $buku->cover_url = $buku->cover;
            } else {
                $buku->cover_url = asset('storage/' . $buku->cover);
            }
        } else {
            $buku->cover_url = null;
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail buku berhasil diambil',
            'data' => $buku
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun' => 'required|string|max:4',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'lokasi_rak' => 'nullable|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('cover');

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku = Buku::create($data);
        if ($buku->cover) {
            if (str_starts_with($buku->cover, 'http')) {
                $buku->cover_url = $buku->cover;
            } else if (str_starts_with($buku->cover, 'assets/')) {
                $buku->cover_url = $buku->cover;
            } else {
                $buku->cover_url = asset('storage/' . $buku->cover);
            }
        } else {
            $buku->cover_url = null;
        }

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil ditambahkan.',
            'data' => $buku
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan'], 404);
        }

        $request->validate([
            'judul' => 'required|string|max:255',
            'pengarang' => 'required|string|max:255',
            'penerbit' => 'required|string|max:255',
            'tahun' => 'required|string|max:4',
            'kategori' => 'required|string|max:100',
            'stok' => 'required|integer|min:0',
            'lokasi_rak' => 'nullable|string|max:255',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except(['cover', '_method']);

        if ($request->hasFile('cover')) {
            if ($buku->cover) {
                Storage::disk('public')->delete($buku->cover);
            }
            $data['cover'] = $request->file('cover')->store('covers', 'public');
        }

        $buku->update($data);
        if ($buku->cover) {
            if (str_starts_with($buku->cover, 'http')) {
                $buku->cover_url = $buku->cover;
            } else if (str_starts_with($buku->cover, 'assets/')) {
                $buku->cover_url = $buku->cover;
            } else {
                $buku->cover_url = asset('storage/' . $buku->cover);
            }
        } else {
            $buku->cover_url = null;
        }

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil diperbarui.',
            'data' => $buku
        ], 200);
    }

    public function destroy($id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan'], 404);
        }

        if ($buku->cover) {
            Storage::disk('public')->delete($buku->cover);
        }

        $buku->delete();

        return response()->json([
            'success' => true,
            'message' => 'Buku berhasil dihapus.'
        ], 200);
    }
}
