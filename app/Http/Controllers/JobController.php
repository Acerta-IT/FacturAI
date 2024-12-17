<?php

namespace App\Http\Controllers;

use App\Models\Job;

class JobController extends Controller
{
    public function index()
    {
        $pendingJobs = Job::orderBy('created_at', 'desc')->paginate(10);

        return view('jobs.index', compact('pendingJobs'));
    }
}
