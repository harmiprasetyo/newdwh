<?php

namespace App\Services;

use App\Models\UsersApp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAppService
{


public function register($data)
{
    $plainToken = (string) Str::uuid();

    // hash untuk lookup
    $hashedToken = hash('sha256', $plainToken);

    $username = substr(str_replace('-', '', $plainToken), 0, 12);

    $user = UsersApp::create([
        'userid' => Str::uuid(),
        'username' => $username,
        'groupid' => $data['groupid'] ?? 3,
        'email' => $data['email'],
        'namalengkap' => $data['namalengkap'],
        'kodeFaskes' => $data['kodefaskes'] ?? null,
        'namaFaskes' => $data['namafaskes'] ?? null,
        'kodePropinsi' => $data['kodeprovinsi'] ?? null,
        'kodeKota' => $data['kodekota'] ?? null,
        'kodeKecamatan' => $data['kodekecamatan'] ?? null,

        // password boleh random saja
        'password' => Hash::make(Str::random(16)),

        // simpan sha256 (BUKAN bcrypt)
        'api_token' => $hashedToken
    ]);

    return [
        'user' => $user,
        'token' => $plainToken,
        "redirectURL"=> route('ssologin') . '?token=' . $plainToken
    ];
}






public function login($data)
{
    $hashed = hash('sha256', $data['token']);

    $user = UsersApp::where('api_token', $hashed)->first();

    if (!$user) {
        throw new \Exception("Token tidak valid");
    }

    $token = $user->createToken('users-app-token')->plainTextToken;

    return [
        'user' => $user,
        'token' => $token,
    ];
}

}
