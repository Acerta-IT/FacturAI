<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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
        $this->dispatch('filesSelected', filePaths: [], tempDir: '');
        $this->reset('files');
    }

    public function updatedFiles()
    {
        try {
            // Create a temporary directory
            $tempDir = storage_path('app/temp/' . Str::uuid());
            File::makeDirectory($tempDir, 0755, true);

            Log::info('a- Temp dir: ' . $tempDir);

            $filePaths = collect($this->files)->map(function($file) use ($tempDir) {
                $newPath = $tempDir . '/' . $file->getClientOriginalName();
                rename($file->getRealPath(), $newPath);
                return $newPath;
            })->toArray();

            Log::info('a - File paths count: ' . count($filePaths));

            Log::info('a - File paths: ' . json_encode($filePaths));

            $this->dispatch('filesSelected', filePaths: $filePaths, tempDir: $tempDir);
        } finally {
            $this->isUploading = false;
            $this->dispatch('uploadFinished');
        }
    }

    public function render()
    {
        return view('livewire.file-input');
    }
}
