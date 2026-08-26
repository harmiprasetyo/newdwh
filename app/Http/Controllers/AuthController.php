<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\UsersApp;
use App\Services\ActivityLogService;

class AuthController extends Controller
{
    public function __construct()
    {
        // kalau sudah login, tidak boleh ke halaman login
        $this->middleware('guest')->only(['index', 'login']);

        // hanya user login yang boleh logout & akses home
        $this->middleware('auth')->only(['logout', 'home']);
    }

    // =========================================================
    // HALAMAN LOGIN
    // =========================================================

    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('homepage');
        }

        return view('login.loginform');
    }


    // =========================================================
    // PROSES LOGIN
    // =========================================================

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);


        /*
        |--------------------------------------------------------------------------
        | LOGIN BERHASIL
        |--------------------------------------------------------------------------
        */

        if (Auth::attempt([
            'username' => $request->username,
            'password' => $request->password
        ])) {

            // Security
            $request->session()->regenerate();

            // Ambil user
            $user = Auth::user();


            /*
            |--------------------------------------------------------------------------
            | SIMPAN SESSION
            |--------------------------------------------------------------------------
            */

            session([
                'group' => $user->groupid,
                'kodeFaskes' => $user->kodeFaskes,
                'kab' => $user->kodeKota,
                'prop' => $user->kodePropinsi
            ]);


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY LOG
            |--------------------------------------------------------------------------
            */

            ActivityLogService::log(
                'login',
                'auth',
                'Login berhasil'
            );


            return response()->json([
                'success' => true,
                'redirect' => route('homepage')
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN GAGAL
        |--------------------------------------------------------------------------
        */

        ActivityLogService::log(
            'login_failed',
            'auth',
            'Login gagal - username atau password salah'
        );


        return response()->json([
            'success' => false,
            'message' => 'Username atau password salah'
        ], 401);
    }


    // =========================================================
    // HOME
    // =========================================================

    public function home()
    {
        if (auth()->user()->groupid == 3) {

            return view('homepage');

        } elseif (auth()->user()->groupid == 1) {

            return view('homepageadmin');

        } else {

            return view('homepage');
        }
    }


    // =========================================================
    // LOGOUT
    // =========================================================

    public function logout(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG SEBELUM LOGOUT
        |--------------------------------------------------------------------------
        */

        ActivityLogService::log(
            'logout',
            'auth',
            'User logout'
        );


        /*
        |--------------------------------------------------------------------------
        | LOGOUT
        |--------------------------------------------------------------------------
        */

        Auth::logout();

        // invalidate session
        $request->session()->invalidate();

        // regenerate CSRF token
        $request->session()->regenerateToken();

        return redirect('/login');
    }


    // =========================================================
    // SSO LOGIN
    // =========================================================

    public function loginsso(Request $request)
    {
        $request->validate([
            'token' => 'required'
        ]);


        /*
        |--------------------------------------------------------------------------
        | CARI USER BERDASARKAN TOKEN
        |--------------------------------------------------------------------------
        */

        $user = UsersApp::where(
            'api_token',
            hash('sha256', $request->token)
        )->first();


        /*
        |--------------------------------------------------------------------------
        | TOKEN TIDAK VALID
        |--------------------------------------------------------------------------
        */

        if (!$user) {

            ActivityLogService::log(
                'login_failed',
                'auth',
                'SSO login gagal - token tidak valid'
            );

            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid'
            ], 401);
        }


        /*
        |--------------------------------------------------------------------------
        | LOGIN MANUAL
        |--------------------------------------------------------------------------
        */

        Auth::login($user);

        // regenerate session
        $request->session()->regenerate();


        /*
        |--------------------------------------------------------------------------
        | SIMPAN SESSION
        |--------------------------------------------------------------------------
        */

        session([
            'group' => $user->groupid,
            'kodeFaskes' => $user->kodeFaskes,
            'kab' => $user->kodeKota,
            'prop' => $user->kodePropinsi
        ]);


        /*
        |--------------------------------------------------------------------------
        | ACTIVITY LOG
        |--------------------------------------------------------------------------
        */

        ActivityLogService::log(
            'login',
            'auth',
            'SSO login berhasil'
        );


        return redirect()->route('homepage');
    }
}
