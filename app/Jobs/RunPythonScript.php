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
        public string $projectDir,
        public string $clientName,
        public string $outputFilename,
        public string $projectId
    ) {
        Log::info('a - Inside constructor');
        Log::info('a - Temp dir: ' . $projectDir);
        Log::info('a - Client name: ' . $clientName);
        Log::info('a - Output filename: ' . $outputFilename);
        Log::info('a - Project ID: ' . $projectId);
    }

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
                'status' => 'starting',
                'temp_dir' => $this->projectDir,
                'output_filename' => $this->outputFilename,
                'project_id' => $this->projectId
            ]);

            $controller = new FacturAIController();
            $controller->execute($this->projectDir, $this->clientName, $this->projectId);

            DB::table('completed_jobs')->insert([
                'client_name' => $this->clientName,
                'created_at' => Carbon::createFromTimestamp($job->created_at)->setTimezone('Europe/Madrid'),
                'completed_at' => Carbon::now()->setTimezone('Europe/Madrid'),
                'reserved_at' => Carbon::createFromTimestamp($job->reserved_at)->setTimezone('Europe/Madrid'),
                'output_filename' => $this->outputFilename,
                'project_id' => $this->projectId
            ]);

        } catch (\Exception $e) {
            event(new JobListUpdateEvent());
            throw $e;
        } finally {
            event(new JobListUpdateEvent());
            event(new CompletedJobListUpdateEvent());
        }
    }
}
