<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\otp_table;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class OtpController extends Controller
{


public function sendOtp(Request $request)
{
    $identifier = $request->identifier;
    $nama = $request->nama; // email / no hp

    $otp = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

    otp_table::create([
        'identifier' => $identifier,
        'otp' => $otp,
        'expires_at' => Carbon::now()->addMinutes(5)
    ]);

    // Kirim OTP (dummy dulu)
   // \Log::info("OTP untuk $identifier: $otp");

  Http::withHeaders([
    'Authorization' => 'Bearer ' . env('WA_TOKEN'),
])->post(env('WA_SERVICE')."api/wa/send-text", [
    'to' => $identifier,
    'text' => "OTP E-Klinik : Kepada Yth, ".ucwords($nama)."  Ada Permintaan Ijin membuka rekam medis anda, dengan kode OTP $otp . kode ini hanya berlaku 5 menit"
]);


    return response()->json([
        'success' => true,
        'message' => 'OTP dikirim'
    ]);
}


public function verifyOtp(Request $request)
{
    $request->validate([
        'identifier' => 'required',
        'otp' => 'required'
    ]);

    $otpData = otp_table::where('identifier', $request->identifier)
        ->where('is_used', false)
        ->latest()
        ->first();

    if (!$otpData) {
        return response()->json([
            'success' => false,
            'message' => 'OTP tidak ditemukan'
        ]);
    }

    // cek expired
    if (now()->greaterThan($otpData->expires_at)) {
        return response()->json([
            'success' => false,
            'message' => 'OTP sudah kadaluarsa'
        ]);
    }

    // cek limit percobaan
    if ($otpData->attempts >= 3) {
        return response()->json([
            'success' => false,
            'message' => 'Terlalu banyak percobaan'
        ]);
    }

    // cek OTP
    if ($otpData->otp !== $request->otp) {

        $otpData->increment('attempts');

        return response()->json([
            'success' => false,
            'message' => 'OTP salah'
        ]);
    }

    // sukses
    $otpData->update([
        'is_used' => true
    ]);

    return response()->json([
        'success' => true,
        'message' => 'OTP valid'
    ]);
}

}
