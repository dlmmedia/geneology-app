<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Get storage path from environment variable (set in bootstrap/vercel.php)
$basePath = dirname(__DIR__);
$storagePath = $_ENV['LARAVEL_STORAGE_PATH'] ?? null;

// If storage path is set, pass it to configure()
$app = $storagePath 
    ? Application::configure(basePath: $basePath, storagePath: $storagePath)
    : Application::configure(basePath: $basePath);

return $app
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        
        $middleware->web(append : [
            App\Http\Middleware\Localization::class,

            // App\Http\Middleware\LogAllRequests::class,
        ]);

        // $middleware->alias([]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();
