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
            'no_hp' => 'nullable|string|max:20|unique:users,no_hp',
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

    public function requestSupport(Request $request)
    {
        $request->validate([
            'username_or_nis' => 'required|string',
            'message' => 'required|string'
        ]);

        $user = User::where('username', $request->username_or_nis)
            ->orWhere('nis', $request->username_or_nis)
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Akun tidak ditemukan'], 404);
        }

        if ($user->status_aktif) {
            return response()->json(['message' => 'Akun Anda sudah aktif. Silakan login.'], 400);
        }

        $user->support_request = $request->message;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Permintaan bantuan Anda telah dikirim ke Admin. Silakan tunggu.'
        ], 200);
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

    public function sendResetOtp(Request $request)
    {
        $request->validate(['no_hp' => 'required|string']);

        $user = User::where('no_hp', $request->no_hp)->first();
        if (!$user) {
            return response()->json(['message' => 'Nomor WhatsApp tidak terdaftar dalam sistem.'], 404);
        }

        $otp = rand(100000, 999999);
        \Illuminate\Support\Facades\Cache::put('reset_otp_' . $request->no_hp, $otp, now()->addMinutes(5));

        try {
            \Log::info("MENGIRIM OTP RESET KE {$request->no_hp}: {$otp}");
            $fonnteToken = config('services.fonnte.token');

            if (empty($fonnteToken)) {
                return response()->json(['success' => false, 'message' => 'Konfigurasi server (Fonnte) belum lengkap.'], 500);
            }

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
                CURLOPT_POSTFIELDS => array(
                    'target' => $request->no_hp,
                    'message' => "Kode OTP Reset Password Anda: $otp. Jangan berikan kode ini ke siapapun."
                ),
                CURLOPT_HTTPHEADER => array('Authorization: ' . $fonnteToken),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                return response()->json(['success' => false, 'message' => 'Gagal mengirim OTP (cURL Error).'], 500);
            }

            return response()->json(['success' => true, 'message' => 'OTP Reset Password berhasil dikirim.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim OTP: ' . $e->getMessage()], 500);
        }
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string',
            'otp' => 'required|string'
        ]);

        $cachedOtp = \Illuminate\Support\Facades\Cache::get('reset_otp_' . $request->no_hp);

        if ($cachedOtp && $cachedOtp == $request->otp) {
            return response()->json(['success' => true, 'message' => 'OTP terverifikasi. Silakan masukkan password baru.']);
        }

        return response()->json(['success' => false, 'message' => 'Kode OTP salah atau sudah kedaluwarsa'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string',
            'otp' => 'required|string',
            'password' => 'required|string|min:6'
        ]);

        $cachedOtp = \Illuminate\Support\Facades\Cache::get('reset_otp_' . $request->no_hp);

        if ($cachedOtp && $cachedOtp == $request->otp) {
            $user = User::where('no_hp', $request->no_hp)->first();
            if ($user) {
                $user->password = Hash::make($request->password);
                $user->save();
                \Illuminate\Support\Facades\Cache::forget('reset_otp_' . $request->no_hp);

                return response()->json(['success' => true, 'message' => 'Password berhasil direset.']);
            }
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan.'], 404);
        }

        return response()->json(['success' => false, 'message' => 'Kode OTP Reset salah/kedaluwarsa (Silakan ulang).'], 400);
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
