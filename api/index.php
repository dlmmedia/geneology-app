<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Configure storage for Vercel (read-only filesystem)
// We force the storage path to /tmp which is writable in Vercel Lambda
$storagePath = '/tmp/storage';
$app->useStoragePath($storagePath);

// Ensure the storage directories exist
if (!is_dir($storagePath)) {
    mkdir($storagePath, 0777, true);
    // Create necessary subdirectories
    $directories = [
        '/framework/views',
        '/framework/cache',
        '/framework/sessions',
        '/logs',
        '/app',
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($storagePath . $dir)) {
            mkdir($storagePath . $dir, 0777, true);
        }
    }
}

$app->handleRequest(Request::capture());
