<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;

class AuthController extends Controller
{





public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {
            return response()->json([
                'message' => 'Username / Password salah'
            ], 401);
        }

        $user = User::with('group.permissions')->find(Auth::id());

        return response()->json([
            'message' => 'Login berhasil',
            'user' => $user,
            'permissions' => $user->group?->permissions->pluck('slug')
        ]);
    }

    public function me()
    {
        return response()->json(Auth::user()->load('group.permissions'));
    }

    public function logout()
    {
        Auth::logout();

        return response()->json([
            'message' => 'Logout berhasil'
        ]);
    }
}
