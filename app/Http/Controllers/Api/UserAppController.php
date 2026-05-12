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
$request->merge([
    'groupid' => $request->groupid ?? 3 // default Puskesmas/RS
]);

$request->validate([
        'email' => 'required|email|unique:users_app',
        'namalengkap' => 'required',
        'groupid' => 'integer',
        'kodefaskes' => 'required',
        'kodeprovinsi' => 'required',
        'kodekota' => 'required'
    ]);

    $result = $this->service->register($request->all());

    return response()->json([
        'message' => 'User created',
        'token' => $result['token'],
        'user' => $result['user'],
        "redirectURL" => $result['redirectURL']
    ]);
}

    public function login(Request $request)
    {
        try {
            $result = $this->service->login($request->all());

            return response()->json([
                'token' => $result['token'],
                'user' => $result['user'],
                'status' => 'success'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error'
            ], 401);
        }
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }
}
