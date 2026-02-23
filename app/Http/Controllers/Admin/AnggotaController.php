<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AnggotaController extends Controller
{
    public function index(Request $request)
    {
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

        $anggotas = $query->orderBy('name')->paginate(10);

        return view('admin.anggota.index', compact('anggotas'));
    }

    public function create()
    {
        return view('admin.anggota.create');
    }

    public function store(Request $request)
    {
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

        User::create([
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

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    public function edit(User $anggotum)
    {
        return view('admin.anggota.edit', ['anggota' => $anggotum]);
    }

    public function update(Request $request, User $anggotum)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $anggotum->id,
            'email' => 'required|email|max:255|unique:users,email,' . $anggotum->id,
            'nis' => 'required|string|max:20',
            'kelas' => 'required|string|max:50',
            'no_hp' => 'nullable|string|max:20',
            'status_aktif' => 'required|boolean',
        ]);

        $data = $request->only(['name', 'username', 'email', 'nis', 'kelas', 'no_hp', 'status_aktif']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $anggotum->update($data);

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    public function destroy(User $anggotum)
    {
        $anggotum->delete();

        return redirect()->route('admin.anggota.index')
            ->with('success', 'Anggota berhasil dihapus.');
    }
}
