<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $query = User::where('role', 'siswa');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
                    ->orWhere('kelas', 'like', "%{$search}%")
                    ->orWhere('no_anggota', 'like', "%{$search}%");
            });
        }

        $anggotas = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'message' => 'Data anggota berhasil diambil',
            'data' => $anggotas
        ], 200);
    }

    public function store(Request $request)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nis' => 'required|string|max:20',
            'kelas' => 'required|string|max:50',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $lastAnggota = User::where('role', 'siswa')
            ->orderBy('id', 'desc')
            ->first();
        $nextNum = $lastAnggota ? (intval(substr($lastAnggota->no_anggota, 4)) + 1) : 1;
        $noAnggota = 'SIS-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

        $anggota = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'no_hp' => $request->no_hp,
            'no_anggota' => $noAnggota,
            'status_aktif' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil ditambahkan',
            'data' => $anggota
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $anggota = User::where('role', 'siswa')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $anggota->id,
            'email' => 'required|email|max:255|unique:users,email,' . $anggota->id,
            'nis' => 'required|string|max:20',
            'kelas' => 'required|string|max:50',
            'no_hp' => 'nullable|string|max:20',
            'status_aktif' => 'required|boolean',
        ]);

        $data = $request->only(['name', 'username', 'email', 'nis', 'kelas', 'no_hp', 'status_aktif']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $anggota->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil diperbarui',
            'data' => $anggota
        ], 200);
    }

    public function destroy($id)
    {
        if (!Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $anggota = User::where('role', 'siswa')->findOrFail($id);
        $anggota->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anggota berhasil dihapus',
        ], 200);
    }
}
