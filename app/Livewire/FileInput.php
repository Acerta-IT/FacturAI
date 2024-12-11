<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class FileInput extends Component
{
    use WithFileUploads;
    public $files;
    public $isUploading = false;

    /* public function updatingFiles()
    {
        $this->isUploading = true;
        $this->dispatch('uploadStarted');
    } */

    public function updatedFiles()
    {
        try {
            // Create a temporary directory
            $tempDir = storage_path('app/temp/' . Str::uuid());
            File::makeDirectory($tempDir, 0755, true);

            $filePaths = collect($this->files)->map(function($file) use ($tempDir) {
                $newPath = $tempDir . '/' . $file->getClientOriginalName();
                rename($file->getRealPath(), $newPath);
                return $newPath;
            })->toArray();

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
