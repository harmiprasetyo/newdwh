<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Api\ApiKey;

class ApiKeyTenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $apiKeyValue = $request->header('X-API-KEY');

        if (!$apiKeyValue) {
            return response()->json([
                'message' => 'X-API-KEY header required'
            ], 401);
        }

        $apiKey = ApiKey::with('tenant')
            ->where('key', $apiKeyValue)
            ->where('is_active', true)
            ->first();

        if (!$apiKey) {
            return response()->json([
                'message' => 'Invalid API Key'
            ], 401);
        }

        $tenant = $apiKey->tenant;

        // 🔥 Logic utama: cek IP hanya jika production
        if ($tenant->environment === 'production') {
            $allowedIps = $tenant->ip_whitelist ?? [];

            /*if (!in_array($request->ip(), $allowedIps)) {
                return response()->json([
                    'message' => 'IP not allowed'
                ], 403);
            }*/
        }

        // inject tenant ke request biar bisa dipakai di controller
        $request->attributes->set('tenant', $tenant);

        return $next($request);
    }
}
