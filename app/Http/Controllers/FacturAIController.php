<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
class FacturAIController extends Controller
{
    public function index()
    {
        return view('facturai');
    }

    public function execute($project_dir, $client_name, $project_id)
    {
        try {
            // Update the config with the project directory
            $this->updateConfig($project_dir, $client_name, $project_id);

            // Execute the Python script
            $command = sprintf(
                '%s "%s"',
                config("facturai.python_command"),
                config("facturai.script_path")
            );

            // Get the output
            $scriptOutput = [];
            $scriptResult = -1;
            exec($command, $scriptOutput, $scriptResult);
            Log::info("Command: " . $command);
            Log::info('----- Output from script -----');
            Log::info('Script output: ' . implode("\n", $scriptOutput));
            Log::info('----- End of output from script -----');
            Log::info('Script result: ' . $scriptResult);
            // Check if the script executed successfully
            if ($scriptResult === 0) {
                // Prepare the file for download
                $config = json_decode(File::get(config("facturai.config_path")), true);
                $filename = $project_id . '_' . $config['excel_output_name'] . '.xlsx';
                $outputFilePath = $project_dir . '/' . $filename;

                // Check if the output file exists
                if (File::exists($outputFilePath)) {
                    Log::info('Output file exists: ' . $outputFilePath);
                    // Move the file to a permanent location (e.g., public directory)
                    $permanentFilePath = public_path('downloads/' . $filename);
                    File::copy($outputFilePath, $permanentFilePath);

                    // Return the file for download
                    return response()->download($permanentFilePath);
                } else {
                    Log::info('Output file does not exist: ' . $outputFilePath);
                    return redirect()->route('facturai.index')->with('status', [
                        'message' => 'El archivo de salida no se ha podido generar.',
                        'class' => 'toast-danger'
                    ]);
                }
            } else {
                return redirect()->route('facturai.index')->with('status', [
                    'message' => 'Error al ejecutar el programa',
                    'class' => 'toast-danger'
                ]);
            }

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
