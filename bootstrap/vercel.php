<?php

// Vercel-specific bootstrap for serverless environment

// Set storage path for Vercel
$storagePath = '/tmp/storage';

// Create storage directories if they don't exist
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

// Set environment variables for Laravel paths
$_ENV['LARAVEL_STORAGE_PATH'] = $storagePath;
$_SERVER['LARAVEL_STORAGE_PATH'] = $storagePath;
putenv('LARAVEL_STORAGE_PATH=' . $storagePath);

return $storagePath;

