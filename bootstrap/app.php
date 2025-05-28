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
            'paystack-api' => \App\Http\Middleware\PaystackMiddleware::class,
            'paystack-api2' => \App\Http\Middleware\Paystack2Middleware::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            '/paystack-webhooks',
            '/subscription-callback',
            '/momo-subscription-callback',
            '/card-subscription-callback',
            '/paystack-payload'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
