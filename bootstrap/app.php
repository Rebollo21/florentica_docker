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
    ->withMiddleware(function (Middleware $middleware) {
        // 1. Registramos el alias del Middleware de Roles
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // 2. Comportamiento para invitados (No logueados)
        $middleware->redirectGuestsTo(fn () => abort(405));

        // 3. Excepción de CSRF para que tus sensores puedan enviar datos
        $middleware->validateCsrfTokens(except: [
            '/post.php', 
            '/api/*', // Tip de pro: mejor usa rutas API para los sensores
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create(); // El punto y coma VA AL FINAL de todo