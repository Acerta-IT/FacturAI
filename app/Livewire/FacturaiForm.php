<?php

namespace App\Livewire;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\File;
use App\Events\JobListUpdateEvent;
use Livewire\Component;
use App\Jobs\RunPythonScript;
use Illuminate\Support\Facades\Log;

class FacturaiForm extends Component
{
    use WithFileUploads;

    public $filePaths = [];
    public $clientName;
    public $projectId;
    public $livewireTempDir;
    public $buttonDisabled = false;
    public $fileToDownload;
    public $outputFilename;

    protected $rules = [
        'clientName' => 'required|string|min:1',
        'projectId' => 'required|string|min:1',
        'filePaths' => 'required|array|min:1',
        'filePaths.*' => 'string',
    ];

    protected $file_rules = [
        'filePaths' => 'required|array|min:1',
        'filePaths.*' => 'string',
    ];

    protected $messages = [
        'filePaths.required' => 'Debe seleccionar al menos un archivo.',
        'filePaths.min' => 'Debe seleccionar al menos un archivo.',
        'filePaths.*.string' => 'Los archivos seleccionados no son válidos.',
        'clientName.required' => 'El nombre del cliente es obligatorio.',
        'clientName.string' => 'El nombre del cliente debe ser texto.',
        'clientName.min' => 'El nombre del cliente no puede estar vacío.',
        'projectId.required' => 'El ID del proyecto es obligatorio.',
        'projectId.string' => 'El ID del proyecto debe ser texto.',
        'projectId.min' => 'El ID del proyecto no puede estar vacío.',
    ];

    protected $listeners = ['filesSelected' => 'updateDirectoryPath', 'uploadStarted' => 'uploadStarted', 'uploadFinished' => 'uploadFinished'];

    public function uploadStarted()
    {
        $this->buttonDisabled = true;
    }

    public function uploadFinished()
    {
        $this->buttonDisabled = false;
    }

    public function updateDirectoryPath($filePaths, $livewireTempDir)
    {
        Log::info('b - inside updateDirectoryPath');
        Log::info('b - File paths: ' . json_encode($filePaths));
        Log::info('b - Temp dir: ' . $livewireTempDir);
        $this->filePaths = $filePaths;
        $this->livewireTempDir = $livewireTempDir;
    }

    public function execute()
    {
        $this->validate();

        try {
            // Check if AnexoII exists in the uploaded files
            $anexoFound = false;
            $config_file = json_decode(File::get(config("facturai.config_path")), true);
            $anexoName = $config_file["excel_input_name"];

            foreach ($this->filePaths as $filePath) {
                if (File::exists($filePath) && str_contains(basename($filePath), $anexoName)) {
                    $anexoFound = true;
                    break;
                }
            }

            if (!$anexoFound) {
                $this->dispatch('show-toast', [
                    'message' => 'No se ha encontrado ningún archivo llamado ' . $anexoName,
                    'class' => 'toast-danger'
                ]);
                return;
            }

            // Create a permanent directory in public/files/{projectId}
            $project_dir = public_path('files/' . $this->projectId);

            // Ensure the directory exists and is empty
            if (File::exists($project_dir)) {
                File::deleteDirectory($project_dir, false);
            }
            File::makeDirectory($project_dir, 0755, true);

            // Copy files from temp to permanent directory
            foreach ($this->filePaths as $filePath) {
                if (File::exists($filePath)) {
                    $fileName = basename($filePath);
                    File::copy($filePath, $project_dir . '/' . $fileName);
                }
            }

            // Get config values
            $config_file_path = config("facturai.config_path");
            $config_file = json_decode(File::get($config_file_path), true);
            $outputFilename = $config_file["excel_output_name"];
            $filename = $this->projectId . "_" . $outputFilename . ".xlsx";

            // Dispatch job with permanent directory path
            Log::info('Dispatching job with client directory: ' . $project_dir);
            RunPythonScript::dispatch($project_dir, $this->clientName, $filename, $this->projectId);
            event(new JobListUpdateEvent());

            // Clear form after successful dispatch
            $this->clientName = "";
            $this->projectId = "";
            $this->dispatch('clearFiles');

            $this->dispatch('show-toast', [
                'message' => 'Procesamiento iniciado. La descarga estará disponible cuando termine.',
                'class' => 'toast-success'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in execute: ' . $e->getMessage());
            $this->dispatch('show-toast', [
                'message' => $e->getMessage(),
                'class' => 'toast-danger'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.facturai-form');
    }
}
