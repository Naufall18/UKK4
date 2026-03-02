<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Username atau password salah',
            ], 401);
        }

        if (!$user->status_aktif) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif',
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]
        ], 200);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nis' => 'nullable|string|max:20',
            'kelas' => 'nullable|string|max:50',
            'no_hp' => 'nullable|string|max:20',
            'admin_code' => 'nullable|string',
        ]);

        // Secret code to register as admin
        $isAdmin = false;
        if ($request->has('admin_code') && $request->admin_code !== null) {
            if ($request->admin_code === env('ADMIN_SECRET_CODE', 'UKK2026ADMIN')) {
                $isAdmin = true;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode Admin tidak valid'
                ], 403);
            }
        }

        if (!$isAdmin && (empty($request->nis) || empty($request->kelas))) {
            return response()->json([
                'success' => false,
                'message' => 'NIS dan Kelas wajib diisi untuk siswa'
            ], 422);
        }

        // Generate nomor anggota otomatis
        if (!$isAdmin) {
            $lastAnggota = User::where('role', 'siswa')
                ->orderBy('id', 'desc')->first();
            $nextNum = $lastAnggota
                ? (intval(substr($lastAnggota->no_anggota, 4)) + 1)
                : 1;
            $noAnggota = 'SIS-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
        } else {
            $noAnggota = null;
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $isAdmin ? 'admin' : 'siswa',
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'no_hp' => $request->no_hp,
            'no_anggota' => $noAnggota,
            'status_aktif' => true,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user
            ]
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }

    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ], 200);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'no_hp' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('username')) {
            $user->username = $request->username;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('no_hp')) {
            $user->no_hp = $request->no_hp;
        }
        if ($request->has('password') && !empty($request->password)) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            if ($user->foto_profil && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->foto_profil)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->foto_profil);
            }
            $path = $request->file('foto_profil')->store('profiles', 'public');
            $user->foto_profil = $path;
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => $user
        ], 200);
    }
}
