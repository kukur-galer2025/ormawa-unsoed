<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'ormawa.access' => \App\Http\Middleware\EnsureOrmawaAccess::class,
            'profile.complete' => \App\Http\Middleware\EnsureProfileComplete::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );

        // Rate Limiting: tangkap 429 Too Many Requests dan tampilkan pesan ramah
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Terlalu banyak percobaan. Silakan coba lagi dalam beberapa menit.',
                ], 429);
            }

            $retryAfter = $e->getHeaders()['Retry-After'] ?? 60;

            return redirect()->back()
                ->withInput()
                ->withErrors([
                    'throttle' => "Terlalu banyak percobaan. Silakan coba lagi dalam {$retryAfter} detik.",
                ]);
        });
    })->create();