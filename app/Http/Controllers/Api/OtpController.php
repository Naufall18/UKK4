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
            // For now we log it, or you could add Fonnte / WhatsApp gateway here
            \Log::info("MENGIRIM OTP KE {$request->no_hp}: {$otp}");

            // Integrasi API Fonnte
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $request->no_hp,
                    'message' => "Kode OTP Anda: $otp. Jangan berikan kode ini ke siapapun."
                ),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: ' . env('FONNTE_TOKEN')
                ),
            ));

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
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
