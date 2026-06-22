<?php

$env = env('APP_ENV', 'production');

// Production CSP allows self and Google Fonts
$csp = "default-src 'self'; script-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data:; font-src 'self' https://fonts.gstatic.com;";

// Local development CSP allows Vite dev server (HMR scripts, styles, fonts, and WebSockets) on all loopback forms
if ($env === 'local') {
    $csp = "default-src 'self' http://localhost:5173 ws://localhost:5173 http://127.0.0.1:5173 ws://127.0.0.1:5173 http://[::1]:5173 ws://[::1]:5173; " .
           "script-src 'self' 'unsafe-inline' 'unsafe-eval' http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173; " .
           "style-src 'self' 'unsafe-inline' http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173 https://fonts.googleapis.com; " .
           "img-src 'self' data: http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173; " .
           "font-src 'self' https://fonts.gstatic.com http://localhost:5173 http://127.0.0.1:5173 http://[::1]:5173; " .
           "connect-src 'self' http://localhost:5173 ws://localhost:5173 http://127.0.0.1:5173 ws://127.0.0.1:5173 http://[::1]:5173 ws://[::1]:5173;";
}

return [
    'headers' => [
        'X-Frame-Options'        => 'DENY',
        'X-Content-Type-Options' => 'nosniff',
        'X-XSS-Protection'       => '1; mode=block',
        'Referrer-Policy'        => 'strict-origin-when-cross-origin',
        'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',
        'Content-Security-Policy'   => $csp,
    ],
];

