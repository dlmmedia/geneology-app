<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Person;
use App\Models\Team;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

final class UpdatePhotoReferences extends Command
{
    protected $signature = 'photos:update-refs {--team= : Team name}';

    protected $description = 'Update database photo references to match existing files - Quick fix';

    public function handle(): int
    {
        $this->info('=== Updating Photo References ===');
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

        $updated = 0;
        $skipped = 0;
        $missing = 0;

        foreach ($people as $person) {
            $personPath = "{$person->team_id}/{$person->id}";
            
            // Check if directory exists
            if (!Storage::disk('photos')->exists($personPath)) {
                $missing++;
                continue;
            }

            $files = Storage::disk('photos')->files($personPath);
            if (empty($files)) {
                $missing++;
                continue;
            }

            // Find first original file (not a size variant)
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
                
                // Update if different or null
                if ($person->photo !== $filename) {
                    $person->update(['photo' => $filename]);
                    $updated++;
                    $this->line("  ✓ Updated: {$person->name} (ID: {$person->id}) -> {$filename}");
                } else {
                    $skipped++;
                }
            } else {
                $missing++;
            }
        }

        $this->newLine();
        $this->info("Summary:");
        $this->line("  Updated: {$updated}");
        $this->line("  Already correct: {$skipped}");
        $this->line("  Missing photos: {$missing}");
        $this->newLine();
        
        if ($updated > 0) {
            $this->info("✓ Successfully updated {$updated} photo references!");
            $this->info("  Photos should now display in the UI.");
        } else {
            $this->info("✓ All photo references are already correct.");
        }

        return self::SUCCESS;
    }
}

