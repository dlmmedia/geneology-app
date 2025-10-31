<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Person;
use App\Models\Team;
use App\PersonPhotos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class EnsureAllPhotos extends Command
{
    protected $signature = 'photos:ensure-all {--team= : Team name}';

    protected $description = 'Ensure all people have correct photo references and download missing photos';

    public function handle(): int
    {
        $this->info('=== Ensure All Photos Are In Place ===');
        $this->newLine();

        $teamName = $this->option('team') ?: 'BRITISH ROYALS';
        $team = Team::where('name', $teamName)->first();

        if (!$team) {
            $this->error("Team '{$teamName}' not found.");
            return self::FAILURE;
        }

        $this->info("Processing team: {$team->name} (ID: {$team->id})");
        $this->newLine();

        $people = Person::where('team_id', $team->id)->orderBy('id')->get();
        $this->info("Found {$people->count()} people to process.");
        $this->newLine();

        $fixed = 0;
        $needsDownload = [];

        // Step 1: Fix database references based on existing files
        $this->info('Step 1: Fixing database references...');
        foreach ($people as $person) {
            $personPath = "{$person->team_id}/{$person->id}";
            
            if (Storage::disk('photos')->exists($personPath)) {
                $files = Storage::disk('photos')->files($personPath);
                
                // Find original file (not a size variant)
                $originalFile = collect($files)->first(function ($file) {
                    $basename = basename($file);
                    return !str_contains($basename, '_large.') && 
                           !str_contains($basename, '_medium.') && 
                           !str_contains($basename, '_small.');
                });

                if ($originalFile) {
                    $filename = pathinfo(basename($originalFile), PATHINFO_FILENAME);
                    
                    if ($person->photo !== $filename) {
                        $person->update(['photo' => $filename]);
                        $fixed++;
                        $this->line("  ✓ Fixed: {$person->name} (ID: {$person->id}) -> {$filename}");
                    }
                }
            } else {
                // No photo files exist
                $needsDownload[] = $person;
            }
        }

        $this->newLine();
        if ($fixed > 0) {
            $this->info("Fixed {$fixed} database references.");
        }

        // Step 2: Download missing photos
        if (!empty($needsDownload)) {
            $this->newLine();
            $this->warn("Found " . count($needsDownload) . " people without photos.");
            
            if ($this->confirm('Download missing photos now?', true)) {
                $this->newLine();
                $successCount = 0;
                $failCount = 0;

                foreach ($needsDownload as $person) {
                    try {
                        if ($this->downloadPhotoForPerson($person)) {
                            $successCount++;
                            $this->line("  ✓ Downloaded: {$person->name} (ID: {$person->id})");
                            
                            // Update database reference after download
                            $personPath = "{$person->team_id}/{$person->id}";
                            $files = Storage::disk('photos')->files($personPath);
                            $originalFile = collect($files)->first(function ($file) {
                                $basename = basename($file);
                                return !str_contains($basename, '_large.') && 
                                       !str_contains($basename, '_medium.') && 
                                       !str_contains($basename, '_small.');
                            });
                            
                            if ($originalFile) {
                                $filename = pathinfo(basename($originalFile), PATHINFO_FILENAME);
                                $person->update(['photo' => $filename]);
                            }
                        } else {
                            $failCount++;
                            $this->line("  ✗ Failed: {$person->name}");
                        }
                        usleep(500000); // Small delay between downloads
                    } catch (\Exception $e) {
                        $failCount++;
                        $this->error("  ✗ Error for {$person->name}: {$e->getMessage()}");
                    }
                }

                $this->newLine();
                $this->info("Download results: {$successCount} succeeded, {$failCount} failed");
            }
        }

        // Step 3: Final verification
        $this->newLine();
        $this->info('Step 3: Verification...');
        $peopleWithPhotos = Person::where('team_id', $team->id)
            ->whereNotNull('photo')
            ->where('photo', '!=', '')
            ->count();
        $peopleWithFiles = 0;

        foreach ($people as $person) {
            $personPath = "{$person->team_id}/{$person->id}";
            if (Storage::disk('photos')->exists($personPath) && 
                !empty(Storage::disk('photos')->files($personPath))) {
                $peopleWithFiles++;
            }
        }

        $this->line("  People with database references: {$peopleWithPhotos}/{$people->count()}");
        $this->line("  People with photo files: {$peopleWithFiles}/{$people->count()}");

        $this->newLine();
        $this->info('✓ Complete!');

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
            return false;
        }

        $command = escapeshellarg('python3') . ' ' . 
                   escapeshellarg($scriptPath) . ' ' .
                   escapeshellarg($searchQuery) . ' ' .
                   escapeshellarg($tempFile);

        $output = [];
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

