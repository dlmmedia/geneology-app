<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Person;
use App\Models\Team;
use App\PersonPhotos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FixAllPhotos extends Command
{
    protected $signature = 'photos:fix-all 
                            {--team= : Specific team name}
                            {--download : Download missing photos}';

    protected $description = 'Fix all photo issues: sync database references and download missing photos';

    public function handle(): int
    {
        $this->info('=== Fix All Photos ===');
        $this->newLine();

        $teamName = $this->option('team');
        $download = $this->option('download');

        // Get teams
        if ($teamName) {
            $teams = Team::where('name', $teamName)->get();
            if ($teams->isEmpty()) {
                $this->error("Team '{$teamName}' not found.");
                return self::FAILURE;
            }
        } else {
            $teams = Team::all();
        }

        $this->info('Processing teams:');
        foreach ($teams as $team) {
            $this->line("  - {$team->name} (ID: {$team->id})");
        }
        $this->newLine();

        $people = Person::whereIn('team_id', $teams->pluck('id'))->orderBy('id')->get();
        
        $fixed = 0;
        $needsDownload = [];

        foreach ($people as $person) {
            $personPath = "{$person->team_id}/{$person->id}";
            $hasFiles = Storage::disk('photos')->exists($personPath) && 
                       !empty(Storage::disk('photos')->files($personPath));

            if ($hasFiles) {
                // Find the first original photo file (not a size variant)
                $files = Storage::disk('photos')->files($personPath);
                $originalFile = collect($files)->first(function ($file) {
                    $basename = basename($file);
                    return !str_contains($basename, '_large.') && 
                           !str_contains($basename, '_medium.') && 
                           !str_contains($basename, '_small.');
                });

                if ($originalFile) {
                    $filename = pathinfo(basename($originalFile), PATHINFO_FILENAME);
                    
                    // Update database if needed
                    if ($person->photo !== $filename) {
                        $person->update(['photo' => $filename]);
                        $fixed++;
                        $this->line("  ✓ Fixed: {$person->name} (ID: {$person->id}) -> {$filename}");
                    }
                }
            } else {
                // Check if this person should have a photo based on XML
                $needsDownload[] = $person;
            }
        }

        $this->newLine();
        $this->info("Fixed {$fixed} database references.");
        
        if (!empty($needsDownload)) {
            $this->warn("Found " . count($needsDownload) . " people without photos.");
            
            if ($download && $this->confirm('Download missing photos?', true)) {
                $this->newLine();
                $this->info("Downloading photos...");
                
                $successCount = 0;
                $failCount = 0;
                
                foreach ($needsDownload as $person) {
                    try {
                        if ($this->downloadPhotoForPerson($person)) {
                            $successCount++;
                            $this->line("  ✓ Downloaded: {$person->name}");
                        } else {
                            $failCount++;
                            $this->line("  ✗ Failed: {$person->name}");
                        }
                        usleep(500000); // Small delay
                    } catch (\Exception $e) {
                        $failCount++;
                        $this->error("  ✗ Error for {$person->name}: {$e->getMessage()}");
                    }
                }
                
                $this->newLine();
                $this->info("Download complete: {$successCount} succeeded, {$failCount} failed");
            }
        }

        $this->newLine();
        $this->info('✓ Photo sync complete!');
        
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

