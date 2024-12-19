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
use App\Events\JobListUpdateEvent;
use App\Events\CompletedJobListUpdateEvent;

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
                'client_name' => $this->clientName,
                'status' => 'starting'
            ]);

            $controller = new FacturAIController();
            $controller->execute($this->tempDir, $this->clientName);

            DB::table('completed_jobs')->insert([
                'client_name' => $this->clientName,
                'created_at' => Carbon::createFromTimestamp($job->created_at)->setTimezone('Europe/Madrid'),
                'completed_at' => Carbon::now()->setTimezone('Europe/Madrid'),
                'reserved_at' => Carbon::createFromTimestamp($job->reserved_at)->setTimezone('Europe/Madrid'),
                'output_filename' => $this->outputFilename
            ]);

        } catch (\Exception $e) {
            event(new JobListUpdateEvent());
            throw $e;
        } finally {
            if (File::exists($this->tempDir)) {
                Log::info('Deleting temp dir: ' . $this->tempDir);
                /* File::deleteDirectory($this->tempDir); */
            } else {
                Log::info('Temp dir not found: ' . $this->tempDir);
            }

            event(new JobListUpdateEvent());
            event(new CompletedJobListUpdateEvent());
        }
    }
}
