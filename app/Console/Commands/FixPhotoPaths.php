<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Person;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

final class FixPhotoPaths extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photos:fix-paths {--dry-run : Show what would be changed without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix photo paths by ensuring photos are in directories matching current team IDs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('DRY RUN MODE - No changes will be made');
        }

        $this->info('=== Photo Path Fixer ===');
        $this->newLine();

        // Get team IDs from database
        $britishRoyalsTeam = Team::where('name', 'BRITISH ROYALS')->first();
        $kennedyTeam = Team::where('name', 'KENNEDY')->first();
        $developerTeam = Team::where('name', 'Team _ Developer')->first();

        if (! $britishRoyalsTeam || ! $kennedyTeam) {
            $this->error('Required teams not found in database');
            $this->error('Make sure you have run the seeders first.');

            return self::FAILURE;
        }

        $this->info('Database Team IDs:');
        $this->line("  BRITISH ROYALS: {$britishRoyalsTeam->id}");
        $this->line("  KENNEDY: {$kennedyTeam->id}");
        if ($developerTeam) {
            $this->line("  Developer: {$developerTeam->id}");
        }
        $this->newLine();

        // Check existing photo directories
        $photosPath = storage_path('app/public/photos');
        $existingDirs = [];
        if (is_dir($photosPath)) {
            $dirs = array_filter(glob($photosPath . '/*'), 'is_dir');
            foreach ($dirs as $dir) {
                $teamId = basename($dir);
                $personCount = count(glob($dir . '/*', GLOB_ONLYDIR));
                $existingDirs[$teamId] = $personCount;
            }
        }

        $this->info('Existing photo directories:');
        foreach ($existingDirs as $teamId => $personCount) {
            $this->line("  Team ID {$teamId}: {$personCount} person(s)");
        }
        $this->newLine();

        $fixed = 0;
        $missing = 0;
        $issues = [];

        // Check British Royals photos
        $this->info('Checking BRITISH ROYALS photos...');
        $brPeople = Person::where('team_id', $britishRoyalsTeam->id)
            ->whereNotNull('photo')
            ->get();

        foreach ($brPeople as $person) {
            $expectedPath = "{$person->team_id}/{$person->id}/{$person->photo}_small.webp";
            if (! Storage::disk('photos')->exists($expectedPath)) {
                // Try common old locations
                $oldLocations = [15, 1];
                $found = false;
                foreach ($oldLocations as $oldTeamId) {
                    $oldPath = "{$oldTeamId}/{$person->id}/{$person->photo}_small.webp";
                    if (Storage::disk('photos')->exists($oldPath)) {
                        $issues[] = [
                            'person' => $person,
                            'expected' => $expectedPath,
                            'found' => $oldPath,
                            'action' => 'move',
                        ];
                        $found = true;
                        break;
                    }
                }
                if (! $found) {
                    $issues[] = [
                        'person' => $person,
                        'expected' => $expectedPath,
                        'found' => null,
                        'action' => 'missing',
                    ];
                    $missing++;
                }
            }
        }

        // Check Kennedy photos
        $this->info('Checking KENNEDY photos...');
        $kennedyPeople = Person::where('team_id', $kennedyTeam->id)
            ->whereNotNull('photo')
            ->get();

        foreach ($kennedyPeople as $person) {
            $expectedPath = "{$person->team_id}/{$person->id}/{$person->photo}_small.webp";
            if (! Storage::disk('photos')->exists($expectedPath)) {
                // Try common old locations
                $oldLocations = [16, 1];
                $found = false;
                foreach ($oldLocations as $oldTeamId) {
                    $oldPath = "{$oldTeamId}/{$person->id}/{$person->photo}_small.webp";
                    if (Storage::disk('photos')->exists($oldPath)) {
                        $issues[] = [
                            'person' => $person,
                            'expected' => $expectedPath,
                            'found' => $oldPath,
                            'action' => 'move',
                        ];
                        $found = true;
                        break;
                    }
                }
                if (! $found) {
                    $issues[] = [
                        'person' => $person,
                        'expected' => $expectedPath,
                        'found' => null,
                        'action' => 'missing',
                    ];
                    $missing++;
                }
            }
        }

        // Check Developer team photos
        if ($developerTeam) {
            $this->info('Checking Developer team photos...');
            $devPeople = Person::where('team_id', $developerTeam->id)
                ->whereNotNull('photo')
                ->get();

            foreach ($devPeople as $person) {
                $expectedPath = "{$person->team_id}/{$person->id}/{$person->photo}_small.webp";
                if (! Storage::disk('photos')->exists($expectedPath)) {
                    $oldLocations = [1];
                    $found = false;
                    foreach ($oldLocations as $oldTeamId) {
                        $oldPath = "{$oldTeamId}/{$person->id}/{$person->photo}_small.webp";
                        if (Storage::disk('photos')->exists($oldPath)) {
                            $issues[] = [
                                'person' => $person,
                                'expected' => $expectedPath,
                                'found' => $oldPath,
                                'action' => 'move',
                            ];
                            $found = true;
                            break;
                        }
                    }
                    if (! $found) {
                        $issues[] = [
                            'person' => $person,
                            'expected' => $expectedPath,
                            'found' => null,
                            'action' => 'missing',
                        ];
                        $missing++;
                    }
                }
            }
        }

        if (empty($issues)) {
            $this->info('✓ All photos are in the correct locations!');

            return self::SUCCESS;
        }

        $moveCount = count(array_filter($issues, fn ($i) => $i['action'] === 'move'));

        $this->warn("Found " . count($issues) . " issue(s):");
        $this->line("  {$moveCount} photo(s) need to be moved");
        $this->line("  {$missing} photo(s) are missing");
        $this->newLine();

        if ($moveCount > 0) {
            $this->info('Photos that need to be moved:');
            foreach ($issues as $issue) {
                if ($issue['action'] === 'move') {
                    $person = $issue['person'];
                    $this->line("  Person {$person->id} ({$person->name})");
                    $this->line("    From: {$issue['found']}");
                    $this->line("    To:   {$issue['expected']}");
                }
            }
            $this->newLine();

            if (! $dryRun) {
                if ($this->confirm('Move photos to correct locations?', true)) {
                    foreach ($issues as $issue) {
                        if ($issue['action'] === 'move') {
                            $person = $issue['person'];
                            $sourceDir = storage_path('app/public/photos/' . dirname($issue['found']));
                            $targetDir = storage_path('app/public/photos/' . $person->team_id . '/' . $person->id);

                            // Get all files for this person from source
                            $sourcePersonDir = $sourceDir;
                            if (is_dir($sourcePersonDir)) {
                                // Create target directory
                                if (! is_dir($targetDir)) {
                                    File::makeDirectory($targetDir, 0755, true);
                                    $this->line("Created directory: {$targetDir}");
                                }

                                // Copy all files for this person
                                $files = File::files($sourcePersonDir);
                                foreach ($files as $file) {
                                    $filename = basename($file);
                                    // Only copy files for this person
                                    if (str_starts_with($filename, "{$person->id}_")) {
                                        $target = $targetDir . '/' . $filename;
                                        if (File::copy($file, $target)) {
                                            $this->line("  Copied: {$filename}");
                                            $fixed++;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $this->newLine();
                    $this->info("✓ Moved {$fixed} photo file(s) to correct locations!");
                }
            }
        }

        if ($missing > 0) {
            $this->newLine();
            $this->warn("⚠ {$missing} person(s) have missing photos.");
        }

        return self::SUCCESS;
    }
}

