#!/usr/bin/bin/env php
<?php

/**
 * Direct script to fix photo database references
 * Run with: php scripts/fix_photo_references.php
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Person;
use Illuminate\Support\Facades\Storage;

echo "=== Fixing Photo Database References ===\n\n";

$people = Person::whereNotNull('team_id')->orderBy('id')->get();
$fixed = 0;
$hasPhotos = 0;

foreach ($people as $person) {
    $personPath = "{$person->team_id}/{$person->id}";
    $hasFiles = Storage::disk('photos')->exists($personPath) && 
               !empty(Storage::disk('photos')->files($personPath));

    if ($hasFiles) {
        $hasPhotos++;
        
        // Find the first original photo file (not a size variant)
        $files = Storage::disk('photos')->files($personPath);
        $originalFile = null;
        
        foreach ($files as $file) {
            $basename = basename($file);
            if (!str_contains($basename, '_large.') && 
                !str_contains($basename, '_medium.') && 
                !str_contains($basename, '_small.')) {
                $originalFile = $file;
                break;
            }
        }

        if ($originalFile) {
            $filename = pathinfo(basename($originalFile), PATHINFO_FILENAME);
            
            // Update database if needed
            if ($person->photo !== $filename) {
                $person->update(['photo' => $filename]);
                $fixed++;
                echo "✓ Fixed: {$person->name} (ID: {$person->id}) -> {$filename}\n";
            }
        }
    }
}

echo "\n";
echo "Summary:\n";
echo "  People with photo files: {$hasPhotos}\n";
echo "  Database references fixed: {$fixed}\n";
echo "\n✓ Done!\n";

