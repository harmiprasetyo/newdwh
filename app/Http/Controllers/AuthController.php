<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        // kalau sudah login, tidak boleh ke halaman login
        $this->middleware('guest')->only(['index', 'login']);
        // hanya user login yang boleh logout & akses home
        $this->middleware('auth')->only(['logout', 'home']);
    }

    // halaman login
    public function index()
    {
    if (Auth::check()) {
        return redirect()->route('homepage');
    }
    return view('login.loginform');
    }

    // proses login
    public function login(Request $request)
    {





      $request->validate([
            'userName' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'userName' => $request->userName,
            'password' => $request->password
        ])) {

            // penting untuk security
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'redirect' => route('homepage')
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah'
        ], 401);

    }

    // halaman setelah login
    public function home()
    {

       return view('homepage'); // buat view home.blade.php
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();

        // invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
