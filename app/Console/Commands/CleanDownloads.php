<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanDownloads extends Command
{
    protected $signature = 'downloads:clean';
    protected $description = 'Clean files from downloads folder that are older than 2 months';

    public function handle()
    {
        $path = public_path('downloads');

        if (!is_dir($path)) {
            $this->error("Downloads directory doesn't exist!");
            return 1;
        }

        $count = 0;
        $weeks = config('facturai.deleteFilesAfterWeeks');
        $dateToDelete = now()->subWeeks($weeks)->timestamp;

        foreach (new \DirectoryIterator($path) as $file) {
            if ($file->isDot()) continue;

            if ($file->isFile()) {
                if ($file->getCTime() < $dateToDelete) {
                    try {
                        unlink($file->getRealPath());
                        $count++;
                        $this->info("Deleted: " . $file->getFilename());
                    } catch (\Exception $e) {
                        Log::error("Failed to delete file: " . $file->getFilename(), [
                            'error' => $e->getMessage()
                        ]);
                        $this->error("Failed to delete: " . $file->getFilename());
                    }
                }
            }
        }

        $this->info("Cleaned {$count} old files from downloads folder");
        return 0;
    }
}
