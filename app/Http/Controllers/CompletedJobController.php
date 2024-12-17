<?php

namespace App\Http\Controllers;

use App\Models\CompletedJob;

class CompletedJobController extends Controller
{
    public function index()
    {
        // Remove from downloads folder files created 2 months ago


        $completedJobs = CompletedJob::orderBy('completed_at', 'desc')->paginate(10);

        return view('completed_jobs.index', compact('completedJobs'));
    }
}
