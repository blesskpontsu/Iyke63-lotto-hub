<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'subscribe' => \App\Http\Middleware\SubscriptionMiddleware::class,
            'active' => \App\Http\Middleware\ActiveSubscriptionMiddleware::class,
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
            'paystack' => \App\Http\Middleware\PaystackMiddleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/paystack-webhooks',
            '/paystack2-webhooks',
            '/subscription-callback',
            '/momo-subscription-callback',
            '/card-subscription-callback',
            '/paystack'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
