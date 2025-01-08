<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class FileController extends Controller
{
    public function download(string $projectId, string $filename): BinaryFileResponse
    {
        $path = storage_path("app/projects/{$projectId}/{$filename}");

        abort_if(!file_exists($path), 404);

        return response()->file($path);
    }
}
