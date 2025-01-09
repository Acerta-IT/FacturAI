<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class CleanProjects extends Command
{
    protected $signature = 'projects:clean';
    protected $description = 'Clean project folders that are older than the configured weeks';

    public function handle()
    {
        $path = storage_path('app/projects');

        if (!is_dir($path)) {
            $this->error("Projects directory doesn't exist!");
            return 1;
        }

        $count = 0;
        $weeks = config('facturai.deleteFilesAfterWeeks');
        $dateToDelete = now()->subWeeks($weeks)->timestamp;

        $dateFormatted = date('d-m-Y', $dateToDelete);
        $this->info("Deleting folders older than " . $dateFormatted);

        foreach (new \DirectoryIterator($path) as $dir) {
            if ($dir->isDot()) continue;
            if (!$dir->isDir()) continue;

            $dirPath = $dir->getRealPath();
            $dirTime = $dir->getMTime();

            if ($dirTime <= $dateToDelete) {
                try {
                    File::deleteDirectory($dirPath);
                    $count++;
                } catch (\Exception $e) {
                    Log::error("Failed to delete folder: " . $dir->getFilename(), [
                        'error' => $e->getMessage()
                    ]);
                    $this->error("Failed to delete: " . $dir->getFilename());
                }
            }
        }

        $this->info("Cleaned {$count} old project folders");
        Log::info("Cleaned {$count} old project folders with date previous to " . $dateFormatted);
        return 0;
    }
}
