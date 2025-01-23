<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use App\Events\JobListUpdateEvent;
use App\Events\CompletedJobListUpdateEvent;
use App\Events\JobProcessingEvent;
use Illuminate\Support\Facades\Redis;

class RunPythonScript implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 18000;        // 5 hours in seconds
    public $tries = 1;              // Only try once since it's a long job
    public $failOnTimeout = true;   // Mark as failed if it times out

    public function __construct(
        public string $projectDir,
        public string $clientName,
        public string $outputFilename,
        public string $projectId
    ) {
        Log::info("Job for project: " . $projectId . " created");
    }

    public function handle()
    {
        try {
            // Broadcast that the job started processing
            event(new JobProcessingEvent($this->projectId));

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

            $this->execute($this->projectDir, $this->clientName, $this->projectId);

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

            // if redis key exists, delete them
            Redis::del("job:$this->projectId:current");
            Redis::del("job:$this->projectId:total");

        }
    }

    public function execute($project_dir, $client_name, $project_id)
    {
        try {
            // Update the config with the project directory
            $this->updateConfig($project_dir, $client_name, $project_id);

            // Execute the Python script
            $command = sprintf(
                '%s "%s" 2>&1',
                config("facturai.python_command"),
                config("facturai.script_path")
            );

            $scriptOutput = [];
            $scriptResult = -1;
            exec($command, $scriptOutput, $scriptResult);

            // Log all Python output
            Log::info('----- Output from script -----');
            foreach ($scriptOutput as $line) {
                Log::info('Python output: ' . $line);
            }
            Log::info('----- End of output from script -----');

        } catch (\Exception $e) {
            return redirect()->route('facturai.index')->with('status', [
                'message' => 'Error al ejecutar el programa: ' . $e->getMessage(),
                'class' => 'toast-danger'
            ]);
        }
    }

    private function updateConfig($project_dir, $clientName, $projectId)
    {
        // Read the current config
        $config = json_decode(File::get(config("facturai.config_path")), true);

        // Update the directory path and client name
        $config['directory_path'] = $project_dir;
        $config['client_name'] = $clientName;
        $config['project_id'] = $projectId;
        // Save the updated config
        File::put(config("facturai.config_path"), json_encode($config, JSON_PRETTY_PRINT));
    }
}
