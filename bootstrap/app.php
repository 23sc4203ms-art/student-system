<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //middleware Groups
        $middleware->group('group_middleware', [
            \App\Http\Middleware\MiddlewareOne::class,
            \App\Http\Middleware\MiddlewareTwo::class,
            \App\Http\Middleware\PromtionMW::class,
        ]);
        //Route Middleware
      
        $middleware->alias(['maintenance' => \App\Http\Middleware\DownForMaintenanceMW::class]);
        $middleware->alias(['auth' => \App\Http\Middleware\CheckAuth::class]);

        // Enable CSRF middleware for web routes
        $middleware->web(append: [
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
