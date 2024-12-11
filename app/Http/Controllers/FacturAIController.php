<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FacturAIController extends Controller
{
    protected $configPath = 'C:\Repositories\ProyectoFacturasIDI\src\config.json';
    private $path_to_script = 'C:\Repositories\ProyectoFacturasIDI\src\facturai.py';

    public function index()
    {
        return view('facturai');
    }

    public function execute($directory_path, $client_name)
    {
        try {
            // Update the config with the temporary directory path
            $this->updateConfig($directory_path, $client_name);

            // Execute the Python script
            $command = sprintf(
                'python "%s"',
                $this->path_to_script
            );

            $scriptSuccess = exec($command);

            // Check if the script executed successfully
            if ($scriptSuccess) {
                // Prepare the file for download
                $config = json_decode(File::get($this->configPath), true);
                $outputFileName = $config['excel_output_name'] . '.xlsx';
                $outputFilePath = $directory_path . '/' . $outputFileName;

                // Check if the output file exists
                if (File::exists($outputFilePath)) {
                    // Move the file to a permanent location (e.g., public directory)
                    $permanentFilePath = public_path('downloads/' . $outputFileName);
                    File::copy($outputFilePath, $permanentFilePath);

                    // Return the file for download
                    return response()->download($permanentFilePath);
                } else {
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

    /* public function execute(Request $request)
    {
        $validated = $request->validate([
            'directory_path' => 'required|array',
            'client_name' => 'required|string|min:1',
        ], [
            'directory_path.required' => 'La ruta del directorio es obligatoria.',
            'directory_path.array' => 'La ruta del directorio debe ser un array.',
            'client_name.required' => 'El nombre del cliente es obligatorio.',
            'client_name.string' => 'El nombre del cliente debe ser texto.',
            'client_name.min' => 'El nombre del cliente no puede estar vacío.',
        ]);

        // Retrieve all files
        $files = $request->allFiles()['directory_path'] ?? null;

        // Check if files are uploaded
        if (empty($files)) {
            return redirect()->route('facturai.index')->withInput($request->all())->with('status', [
                'message' => 'No se seleccionaron archivos.',
                'class' => 'toast-danger'
            ]);
        }

        try {
            // Create a temporary directory
            $tempDir = storage_path('app/temp/' . Str::uuid());
            File::makeDirectory($tempDir, 0755, true);

            // Move uploaded files to the temporary directory
            foreach ($files as $file) {
                $file->move($tempDir, $file->getClientOriginalName());
            }

            // Update the config with the temporary directory path
            $this->updateConfig($tempDir, $validated['client_name']);

            // Execute the Python script
            $command = sprintf(
                'python "%s"',
                $this->path_to_script
            );

            $scriptSuccess = exec($command);

            // Check if the script executed successfully
            if ($scriptSuccess) {
                // Prepare the file for download
                $config = json_decode(File::get($this->configPath), true);
                $outputFileName = $config['excel_output_name'] . '.xlsx';
                $outputFilePath = $tempDir . '/' . $outputFileName;

                // Check if the output file exists
                if (File::exists($outputFilePath)) {
                    // Move the file to a permanent location (e.g., public directory)
                    $permanentFilePath = public_path('downloads/' . $outputFileName);
                    File::copy($outputFilePath, $permanentFilePath);

                    // Clean up the temporary directory
                    File::deleteDirectory($tempDir);

                    // Return the file for download
                    return response()->download($permanentFilePath)->deleteFileAfterSend(true);
                } else {
                    // Clean up the temporary directory
                    File::deleteDirectory($tempDir);

                    return redirect()->route('facturai.index')->withInput($request->all())->with('status', [
                        'message' => 'El archivo de salida no se generó.',
                        'class' => 'toast-danger'
                    ]);
                }
            } else {
                // Clean up the temporary directory
                File::deleteDirectory($tempDir);

                return redirect()->route('facturai.index')->withInput($request->all())->with('status', [
                    'message' => 'Error al ejecutar el programa',
                    'class' => 'toast-danger'
                ]);
            }

        } catch (\Exception $e) {
            return redirect()->route('facturai.index')->withInput($request->all())->with('status', [
                'message' => 'Error al ejecutar el programa: ' . $e->getMessage(),
                'class' => 'toast-danger'
            ]);
        }
    } */

    private function updateConfig($directoryPath, $clientName)
    {
        // Read the current config
        $config = json_decode(File::get($this->configPath), true);

        // Update the directory path and client name
        $config['directory_path'] = $directoryPath;
        $config['client_name'] = $clientName;

        // Save the updated config
        File::put($this->configPath, json_encode($config, JSON_PRETTY_PRINT));
    }
}
