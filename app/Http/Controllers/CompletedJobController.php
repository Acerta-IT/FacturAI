<?php

namespace App\Http\Controllers;

use App\Models\CompletedJob;
use Illuminate\Support\Facades\File;

class CompletedJobController extends Controller
{
    public function index()
    {
        $completedJobs = CompletedJob::orderBy('completed_at', 'desc')->paginate(10);
        return view('completed_jobs.index', compact('completedJobs'));
    }

    public function clean()
    {
        // Delete all records from the completed_jobs table
        CompletedJob::truncate();

        // Delete all folders in the projects directory
        $projectsPath = storage_path('app/projects');
        if (File::exists($projectsPath)) {
            File::deleteDirectory($projectsPath);
            File::makeDirectory($projectsPath, 0755, true);
        }

        return redirect()->route('completedJobs.index')->with('status', [
            'message' => 'Se han eliminado todos los proyectos',
            'class' => 'toast-success'
        ]);
    }
}
