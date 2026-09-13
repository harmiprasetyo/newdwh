<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Prevent Clickjacking
        $response->headers->set(
            'X-Frame-Options',
            'SAMEORIGIN'
        );

        // Prevent MIME sniffing
        $response->headers->set(
            'X-Content-Type-Options',
            'nosniff'
        );

        // Referrer Policy
        $response->headers->set(
            'Referrer-Policy',
            'strict-origin-when-cross-origin'
        );

        // Permissions Policy
        $response->headers->set(
            'Permissions-Policy',
            'camera=(), microphone=(), geolocation=(), payment=()'
        );

        // Cross Origin Policies
        $response->headers->set(
            'Cross-Origin-Opener-Policy',
            'same-origin'
        );

        $response->headers->set(
            'Cross-Origin-Resource-Policy',
            'same-origin'
        );

        $response->headers->set(
            'Cross-Origin-Embedder-Policy',
            'require-corp'
        );

   $response->headers->set(
    'Content-Security-Policy',
    "default-src 'self'; " .

    // JavaScript
    "script-src 'self' 'unsafe-inline' 'unsafe-eval' " .
        "https://cdnjs.cloudflare.com " .
        "https://cdn.jsdelivr.net " .
        "https://cdn.datatables.net " .
        "https://code.jquery.com; " .

    // CSS
    "style-src 'self' 'unsafe-inline' " .
        "https://cdnjs.cloudflare.com " .
        "https://cdn.jsdelivr.net " .
        "https://cdn.datatables.net; " .

    // Images
    "img-src 'self' data: blob:; " .

    // Fonts
    "font-src 'self' data: " .
        "https://cdnjs.cloudflare.com " .
        "https://cdn.jsdelivr.net; " .

    // AJAX / API
    "connect-src 'self'; " .

    // Anti clickjacking
    "frame-ancestors 'self'; " .

    // Other restrictions
    "base-uri 'self'; " .
    "form-action 'self';"
);
        return $response;
    }
}
