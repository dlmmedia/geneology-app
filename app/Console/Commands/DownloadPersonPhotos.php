<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Person;
use App\Models\Team;
use App\PersonPhotos;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class DownloadPersonPhotos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:download 
                            {--team= : Specific team name to download photos for}
                            {--limit= : Limit number of people to process}
                            {--skip-existing : Skip people who already have photos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download photos for people from Google Images using Playwright';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Photo Downloader ===');
        $this->newLine();

        $teamName = $this->option('team');
        $limit = $this->option('limit') ? (int) $this->option('limit') : null;
        $skipExisting = $this->option('skip-existing');

        // Get team(s) to process
        if ($teamName) {
            $teams = Team::where('name', $teamName)->get();
            if ($teams->isEmpty()) {
                $this->error("Team '{$teamName}' not found.");
                return self::FAILURE;
            }
        } else {
            // Get all teams
            $teams = Team::all();
        }

        $this->info('Teams to process:');
        foreach ($teams as $team) {
            $this->line("  - {$team->name} (ID: {$team->id})");
        }
        $this->newLine();

        // Get people who need photos
        $query = Person::whereIn('team_id', $teams->pluck('id'));

        if ($skipExisting) {
            $query->whereNull('photo');
        }

        $people = $query->orderBy('id')->get();

        if ($limit) {
            $people = $people->take($limit);
        }

        $this->info("Found {$people->count()} person(s) to process.");
        $this->newLine();

        if ($people->isEmpty()) {
            $this->info('No people found to process.');
            return self::SUCCESS;
        }

        $this->warn('Note: This command requires Playwright MCP server to be available.');
        $this->warn('Photos will be downloaded from Google Images search.');
        $this->newLine();

        if (! $this->confirm('Continue with photo downloads?', true)) {
            return self::SUCCESS;
        }

        $successCount = 0;
        $failCount = 0;
        $skipCount = 0;

        $progressBar = $this->output->createProgressBar($people->count());
        $progressBar->start();

        foreach ($people as $person) {
            $progressBar->advance();

            // Check if person already has photos
            if ($skipExisting && $person->photo) {
                $photoPath = "{$person->team_id}/{$person->id}/{$person->photo}_small.webp";
                if (Storage::disk('photos')->exists($photoPath)) {
                    $skipCount++;
                    continue;
                }
            }

            try {
                $downloaded = $this->downloadPhotoForPerson($person);
                if ($downloaded) {
                    $successCount++;
                } else {
                    $failCount++;
                    $this->newLine();
                    $this->line("  Failed to download photo for: {$person->name} (ID: {$person->id})");
                }
            } catch (\Exception $e) {
                $failCount++;
                $this->newLine();
                $this->error("  Error downloading photo for {$person->name}: {$e->getMessage()}");
            }

            // Small delay to avoid overwhelming servers
            usleep(500000); // 0.5 seconds
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("✓ Completed!");
        $this->line("  Successfully downloaded: {$successCount}");
        $this->line("  Failed: {$failCount}");
        if ($skipCount > 0) {
            $this->line("  Skipped (already have photos): {$skipCount}");
        }

        return self::SUCCESS;
    }

    /**
     * Download a photo for a person from Google Images using Playwright.
     */
    private function downloadPhotoForPerson(Person $person): bool
    {
        // Search query for the person
        $searchQuery = $person->name;
        if ($person->surname) {
            $searchQuery = "{$person->firstname} {$person->surname}";
        }

        // Create temporary directory for downloads
        $tempDir = storage_path('app/temp-photos');
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Temporary file path for downloaded image
        $tempFile = $tempDir . '/' . Str::slug($searchQuery) . '_' . time() . '.jpg';

        // Call Python script with Playwright
        $scriptPath = base_path('scripts/download_photo_with_playwright.py');
        
        if (! file_exists($scriptPath)) {
            $this->error("Python script not found: {$scriptPath}");
            return false;
        }

        // Execute Python script
        $command = escapeshellarg('python3') . ' ' . 
                   escapeshellarg($scriptPath) . ' ' .
                   escapeshellarg($searchQuery) . ' ' .
                   escapeshellarg($tempFile);

        $output = [];
        $returnCode = 0;
        exec($command . ' 2>&1', $output, $returnCode);

        // Parse JSON output
        $outputString = implode("\n", $output);
        $result = json_decode($outputString, true);

        if (! $result || ! ($result['success'] ?? false)) {
            $error = $result['error'] ?? 'Unknown error';
            $this->line("  Error for {$person->name}: {$error}");
            
            // Clean up temp file if it exists
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            
            return false;
        }

        // Save the downloaded photo using PersonPhotos
        if (! file_exists($tempFile)) {
            $this->line("  Downloaded file not found: {$tempFile}");
            return false;
        }

        try {
            $personPhotos = new PersonPhotos($person);
            $savedCount = $personPhotos->save([$tempFile]);

            // Clean up temp file
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }

            if ($savedCount && $savedCount > 0) {
                return true;
            }
        } catch (\Exception $e) {
            $this->error("  Failed to save photo for {$person->name}: {$e->getMessage()}");
            
            // Clean up temp file
            if (file_exists($tempFile)) {
                unlink($tempFile);
            }
            
            return false;
        }

        return false;
    }
}

