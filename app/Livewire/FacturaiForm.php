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
    public $tempDir;
    public $buttonDisabled = false;
    public $fileToDownload;
    public $outputFilename;

    protected $rules = [
        'clientName' => 'required|string|min:1',
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

    public function updateDirectoryPath($filePaths, $tempDir)
    {
        Log::info('b - inside updateDirectoryPath');
        Log::info('b - File paths: ' . json_encode($filePaths));
        Log::info('b - Temp dir: ' . $tempDir);
        $this->filePaths = $filePaths;
        $this->tempDir = $tempDir;
    }

    public function execute()
    {
        Log::info('inside execute');
        $this->validate();

        try {
            // Copy files to temp directory
            foreach ($this->filePaths as $filePath) {
                if (File::exists($filePath)) {
                    $fileName = basename($filePath);
                    File::copy($filePath, $this->tempDir . '/' . $fileName);
                }
            }

            $config_file_path = config("facturai.config_path");
            $config_file = json_decode(File::get($config_file_path), true);
            $outputFilename = $config_file["excel_output_name"];
            $filename = $this->clientName . "_" . $outputFilename . ".xlsx";

            // Dispatch job
            Log::info('Dispatching job');
            // In production, as it seems that the events travel faster than the job is executed, we need to copy the variables to local variables
            // This is because this method triggers the clearFiles event, that ends up calling the updateDirectoryPath method, which sets the filePaths and tempDir to null,
            // and the job is executed with null values
            $auxTempDir = $this->tempDir;
            $auxClientName = $this->clientName;
            $auxFilename = $filename;
            RunPythonScript::dispatch($auxTempDir, $auxClientName, $auxFilename);
            event(new JobListUpdateEvent());

            $this->clientName = "";
            $this->dispatch('clearFiles');

            $this->dispatch('show-toast', [
                'message' => 'Procesamiento iniciado. La descarga estará disponible cuando termine.',
                'class' => 'toast-success'
            ]);

        } catch (\Exception $e) {
            $this->dispatch('show-toast', [
                'message' => $e->getMessage(),
                'class' => 'toast-error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.facturai-form');
    }
}
