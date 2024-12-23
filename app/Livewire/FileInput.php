<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FileInput extends Component
{
    use WithFileUploads;

    public $files;
    public $isUploading = false;

    protected $listeners = ['clearFiles' => 'clearFiles'];

    public function uploadStarted()
    {
        $this->isUploading = true;
    }

    public function clearFiles()
    {
        $this->files = null;
        $this->isUploading = false;
        $this->dispatch('filesSelected', filePaths: [], livewireTempDir: '');
        $this->reset('files');
    }

    public function updatedFiles()
    {
        try {
            // Create a unique folder for this batch of files
            $batchId = Str::uuid();
            $baseDir = storage_path('app/private/livewire-tmp');
            $batchDir = $baseDir . '/' . $batchId;

            // Ensure the directory exists
            if (!File::exists($batchDir)) {
                File::makeDirectory($batchDir, 0755, true);
            }

            $filePaths = collect($this->files)->map(function($file) use ($batchDir) {
                $originalName = $file->getClientOriginalName();
                $currentPath = $file->getRealPath();
                $newPath = $batchDir . '/' . $originalName;

                // Rename the file to its original name in the batch directory
                rename($currentPath, $newPath);

                return $newPath;
            })->toArray();

            Log::info('Files renamed in batch directory: ' . json_encode([
                'batchDir' => $batchDir
            ]));

            $this->dispatch('filesSelected', filePaths: $filePaths, livewireTempDir: $batchDir);
            $this->dispatch('uploadFinished');

        } catch (\Exception $e) {
            Log::error('Error handling files: ' . $e->getMessage());
            throw $e;
        } finally {
            $this->isUploading = false;
        }
    }

    public function render()
    {
        return view('livewire.file-input');
    }
}
