<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // ← Agrega esta línea
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->appendToGroup('web', [
        // ... otros middlewares
    ]);
    
    // Agrega esta línea para excluir las rutas IoT
    $middleware->validateCsrfTokens(except: [
        'iot-alerta',
        'api/*'
    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();