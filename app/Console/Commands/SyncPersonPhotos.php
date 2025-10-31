<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Person;
use App\Models\Team;
use App\PersonPhotos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class SyncPersonPhotos extends Command
{
    protected $signature = 'photos:sync 
                            {--team= : Specific team name}
                            {--fix-database : Update database photo fields based on existing files}
                            {--download-missing : Download missing photos}';

    protected $description = 'Sync person photos: fix database references and download missing photos';

    public function handle(): int
    {
        $this->info('=== Photo Sync Tool ===');
        $this->newLine();

        $teamName = $this->option('team');
        $fixDatabase = $this->option('fix-database');
        $downloadMissing = $this->option('download-missing');

        // Get teams to process
        if ($teamName) {
            $teams = Team::where('name', $teamName)->get();
            if ($teams->isEmpty()) {
                $this->error("Team '{$teamName}' not found.");
                return self::FAILURE;
            }
        } else {
            $teams = Team::all();
        }

        $this->info('Teams to process:');
        foreach ($teams as $team) {
            $this->line("  - {$team->name} (ID: {$team->id})");
        }
        $this->newLine();

        $people = Person::whereIn('team_id', $teams->pluck('id'))->get();
        $this->info("Found {$people->count()} person(s) to process.");
        $this->newLine();

        $stats = [
            'has_files' => 0,
            'has_db_ref' => 0,
            'missing_files' => 0,
            'missing_db_ref' => 0,
            'fixed' => 0,
        ];

        foreach ($people as $person) {
            $personPath = "{$person->team_id}/{$person->id}";
            $hasFiles = Storage::disk('photos')->exists($personPath) && 
                       !empty(Storage::disk('photos')->files($personPath));
            $hasDbRef = !empty($person->photo);

            if ($hasFiles) {
                $stats['has_files']++;
            }
            if ($hasDbRef) {
                $stats['has_db_ref']++;
            }

            // Fix database reference if files exist but DB doesn't have reference
            if ($hasFiles && !$hasDbRef && $fixDatabase) {
                $files = Storage::disk('photos')->files($personPath);
                $photoFile = collect($files)->first(function ($file) {
                    $basename = basename($file);
                    return !str_contains($basename, '_large.') && 
                           !str_contains($basename, '_medium.') && 
                           !str_contains($basename, '_small.');
                });

                if ($photoFile) {
                    $filename = pathinfo(basename($photoFile), PATHINFO_FILENAME);
                    $person->update(['photo' => $filename]);
                    $stats['fixed']++;
                    $this->line("  ✓ Fixed DB reference for: {$person->name} (ID: {$person->id})");
                }
            }

            // Check if missing files
            if (!$hasFiles) {
                $stats['missing_files']++;
                if ($hasDbRef) {
                    $stats['missing_db_ref']++;
                }
            }
        }

        $this->newLine();
        $this->info('Summary:');
        $this->line("  People with photo files: {$stats['has_files']}");
        $this->line("  People with DB references: {$stats['has_db_ref']}");
        $this->line("  People missing files: {$stats['missing_files']}");
        if ($stats['fixed'] > 0) {
            $this->line("  Database references fixed: {$stats['fixed']}");
        }
        $this->newLine();

        // Download missing photos if requested
        if ($downloadMissing && $stats['missing_files'] > 0) {
            $this->info("Downloading missing photos...");
            $missingPeople = Person::whereIn('team_id', $teams->pluck('id'))
                ->get()
                ->filter(function ($person) {
                    $personPath = "{$person->team_id}/{$person->id}";
                    return !Storage::disk('photos')->exists($personPath) || 
                           empty(Storage::disk('photos')->files($personPath));
                });

            $this->info("Found {$missingPeople->count()} people needing photos.");
            
            if ($this->confirm('Download photos for these people?', true)) {
                $successCount = 0;
                $failCount = 0;

                foreach ($missingPeople as $person) {
                    try {
                        $downloaded = $this->downloadPhotoForPerson($person);
                        if ($downloaded) {
                            $successCount++;
                            $this->line("  ✓ Downloaded photo for: {$person->name}");
                        } else {
                            $failCount++;
                        }
                    } catch (\Exception $e) {
                        $failCount++;
                        $this->error("  ✗ Error for {$person->name}: {$e->getMessage()}");
                    }
                }

                $this->newLine();
                $this->info("Download complete: {$successCount} succeeded, {$failCount} failed");
            }
        }

        return self::SUCCESS;
    }

    private function downloadPhotoForPerson(Person $person): bool
    {
        $searchQuery = $person->name;
        if ($person->surname) {
            $searchQuery = "{$person->firstname} {$person->surname}";
        }

        $tempDir = storage_path('app/temp-photos');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempFile = $tempDir . '/' . Str::slug($searchQuery) . '_' . time() . '.jpg';
        $scriptPath = base_path('scripts/download_photo_with_playwright.py');

        if (!file_exists($scriptPath)) {
            $this->error("Python script not found: {$scriptPath}");
            return false;
        }

        $command = escapeshellarg('python3') . ' ' . 
                   escapeshellarg($scriptPath) . ' ' .
                   escapeshellarg($searchQuery) . ' ' .
                   escapeshellarg($tempFile);

        $output = [];
        $returnCode = 0;
        exec($command . ' 2>&1', $output, $returnCode);

        $outputString = implode("\n", $output);
        $result = json_decode($outputString, true);

        if (!$result || !($result['success'] ?? false)) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            return false;
        }

        if (!file_exists($tempFile)) {
            return false;
        }

        try {
            $personPhotos = new PersonPhotos($person);
            $savedCount = $personPhotos->save([$tempFile]);

            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            return $savedCount && $savedCount > 0;
        } catch (\Exception $e) {
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            return false;
        }
    }
}

