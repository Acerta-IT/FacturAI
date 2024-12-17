<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\FacturAIController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class RunPythonScript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $tempDir,
        public string $clientName,
        public string $outputFilename
    ) {}

    public function handle()
    {
        try {
            // Access job information
            $jobId = $this->job->getJobId();
            $job = DB::table('jobs')
                ->where('id', $jobId)
                ->first();

            Log::info('Running Python script', [
                'job_id' => $jobId,
                'created_at' => $job->created_at,
                'reserved_at' => $job->reserved_at
            ]);

            $controller = new FacturAIController();
            $controller->execute($this->tempDir, $this->clientName);

            // Record successful completion using the job's actual creation time
            DB::table('completed_jobs')->insert([
                'client_name' => $this->clientName,
                'created_at' => Carbon::createFromTimestamp($job->created_at)->setTimezone('Europe/Madrid'),
                'completed_at' => Carbon::now()->setTimezone('Europe/Madrid'),
                'reserved_at' => Carbon::createFromTimestamp($job->reserved_at)->setTimezone('Europe/Madrid'),
                'output_filename' => $this->outputFilename
            ]);

        } catch (\Exception $e) {
            throw $e;
        } finally {
            // Clean up the temporary directory
            if (File::exists($this->tempDir)) {
                File::deleteDirectory($this->tempDir);
            }
        }

    }
}
