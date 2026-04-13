<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\SendOtpMail;

class OtpController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string'
        ]);

        $otp = rand(100000, 999999);

        // Cache for 5 minutes
        Cache::put('otp_' . $request->no_hp, $otp, now()->addMinutes(5));

        try {
            \Log::info("MENGIRIM OTP KE {$request->no_hp}: {$otp}");

            // Ambil token dari config (bukan env langsung, agar tetap work saat config cached)
            $fonnteToken = config('services.fonnte.token');

            if (empty($fonnteToken)) {
                \Log::error('FONNTE_TOKEN tidak ditemukan di config. Pastikan FONNTE_TOKEN ada di .env dan jalankan php artisan config:clear');
                return response()->json([
                    'success' => false,
                    'message' => 'Konfigurasi OTP belum lengkap. Hubungi admin.'
                ], 500);
            }

            // Integrasi API Fonnte
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
                    'message' => "Kode OTP Anda: $otp. Jangan berikan kode ini ke siapapun."
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . $fonnteToken
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_close($curl);

            \Log::info("Fonnte API Response [HTTP {$httpCode}]: " . $response);

            if ($err) {
                \Log::error("Fonnte cURL Error: " . $err);
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim OTP (cURL Error): ' . $err
                ], 500);
            }

            $responseData = json_decode($response, true);
            if (isset($responseData['status']) && $responseData['status'] == false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal dari API Fonnte: ' . ($responseData['reason'] ?? 'Unknown error'),
                    'fonnte_response' => $responseData
                ], 500);
            }

            return response()->json(['success' => true, 'message' => 'OTP berhasil dikirim ke WhatsApp/SMS Anda.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mengirim OTP: ' . $e->getMessage()], 500);
        }
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'no_hp' => 'required|string',
            'otp' => 'required|string'
        ]);

        $cachedOtp = Cache::get('otp_' . $request->no_hp);

        if ($cachedOtp && $cachedOtp == $request->otp) {
            // Invalidate OTP after success
            Cache::forget('otp_' . $request->no_hp);
            return response()->json(['success' => true, 'message' => 'OTP terverifikasi']);
        }

        return response()->json(['success' => false, 'message' => 'Kode OTP salah atau sudah kedaluwarsa'], 400);
    }
}
