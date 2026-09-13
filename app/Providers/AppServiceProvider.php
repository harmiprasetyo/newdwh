<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;



class AppServiceProvider extends ServiceProvider
{
   public function boot(): void
{
    RateLimiter::for('login', function (Request $request) {

        $username = Str::lower(
            trim($request->input('username', ''))
        );

        $ip = $request->ip();

        return [
            // Maksimal 3 request login per menit dari satu IP
            Limit::perMinute(3)
                ->by('login-ip:' . $ip),

            // Maksimal 3 request untuk username + IP
            Limit::perMinute(3)
                ->by('login-user:' . $username . '|' . $ip),
        ];
    });
}
}
