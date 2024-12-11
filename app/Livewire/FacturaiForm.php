<?php

namespace App\Livewire;

use Livewire\WithFileUploads;
use App\Http\Controllers\FacturAIController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use Livewire\Component;

class FacturaiForm extends Component
{
    use WithFileUploads;

    public $filePaths = [];
    public $clientName;
    public $tempDir;
    public $buttonDisabled = false;
    public $fileToDownload = null;

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
            // Copy each file to the temporary directory
            foreach ($this->filePaths as $filePath) {
                if (File::exists($filePath)) {
                    $fileName = basename($filePath);
                    File::copy($filePath, $this->tempDir . '/' . $fileName);
                }
            }

            // Execute the script with the temporary directory
            $response = app(FacturAIController::class)->execute($this->tempDir, $this->clientName);
            $this->fileToDownload = $response->getFile()->getPathname();
            /* return $response; */

        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        } finally {
            // Clean up: Delete temporary directory and its contents
            if (isset($this->tempDir) && File::exists($this->tempDir)) {
                File::deleteDirectory($this->tempDir);
            }

            $this->buttonDisabled = false;
        }
    }

    public function render()
    {
        return view('livewire.facturai-form');
    }
}
