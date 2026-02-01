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

        // 🔹 Alias middleware (route middleware)
        $middleware->alias([
            'admin'         => \App\Http\Middleware\AdminMiddleware::class,
            'operator' => \App\Http\Middleware\OperatorMiddleware::class,
            'not.installed' => \App\Http\Middleware\EnsureNotInstalled::class,
            'permission'    => \App\Http\Middleware\CheckPermission::class,
        ]);


        // 🔹 Global WEB middleware
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
            \App\Http\Middleware\ForcePasswordChange::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
