<?php

// NOTE: Laravel 11 registers middleware in bootstrap/app.php by default, not
// this Kernel file. This file is provided for reference / for teams that
// prefer the classic Kernel style. If you use bootstrap/app.php instead,
// see routes/web.php comment + bootstrap-app-snippet.php in this package
// for the equivalent ->withMiddleware() aliases.

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'active' => \App\Http\Middleware\EnsureUserIsActive::class,
        'verified.2fa' => \Laravel\Fortify\Http\Middleware\RedirectIfTwoFactorAuthenticatable::class,
        'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
    ];
}
