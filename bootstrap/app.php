<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(web: __DIR__ . '/../routes/web.php', commands: __DIR__ . '/../routes/console.php', health: '/up')
        ->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        'tcg_validator' => \App\Http\Middleware\EnsureIsValidator::class,
        'not_validator' => \App\Http\Middleware\EnsureIsNotValidator::class,
        'check_shipping_debt' => \App\Http\Middleware\CheckShippingDebt::class,
        'redirect_admin' => \App\Http\Middleware\RedirectAdmin::class,
    ]);
    $middleware->append(\App\Http\Middleware\RedirectAdmin::class);
    $middleware->validateCsrfTokens(except: ['stripe/webhook', 'validator/transaction/*/approve']);
})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
