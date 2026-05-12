<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\UsersApp;
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
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt([
            'username' => $request->username,
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

    if(auth()->user()->groupid == 3) {
        return view('homepage');
    }elseif(auth()->user()->groupid == 1) {
        return view('homepageadmin');
    }else{

       return view('homepage'); // buat view home.blade.php
    }
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


   public function loginsso(Request $request)
{
    $request->validate([
        'token' => 'required'
    ]);

    // cari user berdasarkan token
    $user = UsersApp::where('api_token', hash('sha256', $request->token))->first();

    if (!$user) {
        return response()->json([
            'success' => false,
            'message' => 'Token tidak valid'
        ], 401);
    }

    // 🔥 login manual (pakai session)
    Auth::login($user);

    // regenerate session (BENAR untuk web)
    $request->session()->regenerate();

    /* return response()->json([
        'success' => true,
        'redirect' => route('homepage')
    ]); */
    return redirect()->route('homepage');
}
}
