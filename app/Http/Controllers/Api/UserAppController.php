<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\UserAppService;

class UserAppController extends Controller
{
    protected $service;

    public function __construct(UserAppService $service)
    {
        $this->service = $service;
    }

   public function register(Request $request)
{
    $request->validate([
        'username' => 'required|unique:users_app',
        'email' => 'required|email|unique:users_app',
        'namalengkap' => 'required',
        'password' => 'required|min:6'
    ]);

    $result = $this->service->register($request->all());

    return response()->json([
        'message' => 'User created',
        'token' => $result['token'],
        'user' => $result['user']
    ]);
}

    public function login(Request $request)
    {
        try {
            $result = $this->service->login($request->all());

            return response()->json([
                'token' => $result['token'],
                'user' => $result['user']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 401);
        }
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
