<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        'auth.redirect' => \App\Http\Middleware\RedirectIfAuthenticatedToDashboard::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,

        // ✅ Gunakan class yang benar sesuai nama file
        'is_admin' => \App\Http\Middleware\IsAdmin::class,
    ];
}
