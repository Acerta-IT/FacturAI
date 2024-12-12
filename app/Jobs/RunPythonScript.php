<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\FacturAIController;

class RunPythonScript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $tempDir,
        protected string $clientName
    ) {}

    public function handle()
    {
        try {
            $controller = new FacturAIController();
            $response = $controller->execute($this->tempDir, $this->clientName);

            // Copy result to public directory
            /* $outputFile = $response->getFile()->getPathname();
            File::copy($outputFile, public_path('downloads/Registros-Primarios2.xlsx')); */

            // Cleanup temp directory
            if (File::exists($this->tempDir)) {
                File::deleteDirectory($this->tempDir);
            }
        } catch (\Exception $e) {
            // Handle error
            throw $e;
        }
    }
}
