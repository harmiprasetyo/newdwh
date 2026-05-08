<?php

namespace App\Services;

use App\Models\UsersApp;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAppService
{


public function register($data)
{
    $plainToken = Str::random(60);

    $user = UsersApp::create([
        'userid' => Str::uuid(),
        'username' => $data['username'],
        'groupid' => $data['groupid'] ?? 1,
        'email' => $data['email'],
        'namalengkap' => $data['namalengkap'],
        'kodeFaskes' => $data['kodeFaskes'] ?? null,
        'namaFaskes' => $data['namaFaskes'] ?? null,
        'kodePropinsi' => $data['kodePropinsi'] ?? null,
        'kodeKota' => $data['kodeKota'] ?? null,
        'kodeKecamatan' => $data['kodeKecamatan'] ?? null,
        'password' => Hash::make($data['password']),
        'api_token' => hash('sha256', $plainToken) // disimpan hash
    ]);

    return [
        'user' => $user,
        'token' => $plainToken // kirim token asli ke client
    ];
}

    public function login($data)
    {
        $user = UsersApp::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new \Exception("Username / Password salah");
        }

        $token = $user->createToken('users-app-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
