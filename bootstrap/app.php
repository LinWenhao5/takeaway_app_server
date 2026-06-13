<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\EncryptCookies;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Exceptions\BusinessException;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware alias as an array
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);

        $middleware->append([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            SetLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (BusinessException $e) {
            return response()->json([
                    'error' => $e->getMessage(),
                    'code' => $e->getErrorCode(),
            ], $e->getStatusCode());
        });

        $exceptions->render(function (ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'code' => 'VALIDATION_FAILED',
            'errors' => $e->errors(),
        ], 422);
    });
    })->create();
