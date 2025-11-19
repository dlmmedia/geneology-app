<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    require __DIR__ . '/../vendor/autoload.php';
    
    // Define LARAVEL_START if not defined
    if (!defined('LARAVEL_START')) {
        define('LARAVEL_START', microtime(true));
    }
    
    // Configure storage for Vercel BEFORE bootstrapping (read-only filesystem)
    $storagePath = '/tmp/storage';
    
    // Ensure the storage directories exist BEFORE bootstrap
    if (!is_dir($storagePath)) {
        mkdir($storagePath, 0777, true);
    }
    
    $directories = [
        '/framework/views',
        '/framework/cache',
        '/framework/sessions',
        '/framework/testing',
        '/logs',
        '/app',
        '/app/public',
    ];
    
    foreach ($directories as $dir) {
        if (!is_dir($storagePath . $dir)) {
            mkdir($storagePath . $dir, 0777, true);
        }
    }
    
    // Set environment variable for view compiled path
    if (!getenv('VIEW_COMPILED_PATH')) {
        putenv('VIEW_COMPILED_PATH=' . $storagePath . '/framework/views');
        $_ENV['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';
        $_SERVER['VIEW_COMPILED_PATH'] = $storagePath . '/framework/views';
    }
    
    // Set VERCEL environment variable so bootstrap/app.php can detect it
    if (!isset($_ENV['VERCEL'])) {
        $_ENV['VERCEL'] = '1';
        $_SERVER['VERCEL'] = '1';
        putenv('VERCEL=1');
    }
    
    // Determine if the application is in maintenance mode...
    if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
        require $maintenance;
    }
    
    // Bootstrap Laravel and handle the request...
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    $app->handleRequest(Illuminate\Http\Request::capture());
    
} catch (Throwable $e) {
    http_response_code(500);
    echo "<h1>Error</h1>";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
