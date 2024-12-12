<?php

namespace App\Livewire;

use Livewire\WithFileUploads;
use App\Http\Controllers\FacturAIController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use Livewire\Component;
use App\Jobs\RunPythonScript;

class FacturaiForm extends Component
{
    use WithFileUploads;

    public $filePaths = [];
    public $clientName;
    public $tempDir;
    public $buttonDisabled = false;
    public $isProcessing = false;
    public $fileToDownload;

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
        $this->filePaths = $filePaths;
        $this->tempDir = $tempDir;
    }

    public function execute()
    {
        $this->buttonDisabled = true;
        $this->validate();

        try {
            // Copy files to temp directory
            foreach ($this->filePaths as $filePath) {
                if (File::exists($filePath)) {
                    $fileName = basename($filePath);
                    File::copy($filePath, $this->tempDir . '/' . $fileName);
                }
            }

            // Dispatch job
            RunPythonScript::dispatch($this->tempDir, $this->clientName);
            $this->isProcessing = true;

            session()->flash('message', 'Procesamiento iniciado. La descarga estará disponible cuando termine.');

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        } finally {
            $this->buttonDisabled = false;
        }
    }

    // Add a polling method to check if file is ready
    public function checkFileStatus()
    {
        $filename = $this->clientName . '_Registros-Primarios.xlsx';
        if (File::exists(public_path('downloads/' . $filename))) {
            $this->fileToDownload = 'downloads/' . $filename;
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.facturai-form');
    }
}
