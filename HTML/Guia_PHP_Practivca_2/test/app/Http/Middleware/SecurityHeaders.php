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

        // Skip security headers in local environment to prevent blocking Vite dev server (HMR) or causing HTTP/HTTPS issues
        if (app()->environment('local')) {
            return $response;
        }

        $headers = config('security.headers', []);

        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}
