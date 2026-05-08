<?php

namespace App\Services;

use App\Models\Api\UserApi;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserApiService
{
    public function register($data)
    {
        return UserApi::create([
            'id' => Str::uuid(),
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'client_name' => $data['client_name'] ?? null,
            'kode_faskes' => $data['kode_faskes'] ?? null,
        ]);
    }

    public function login($data)
    {
        $user = UserApi::where('username', $data['username'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw new \Exception("Unauthorized");
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }
}
