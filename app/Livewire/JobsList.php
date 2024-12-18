<?php

namespace App\Livewire;

use App\Models\Job;
use Livewire\Component;
use Livewire\WithPagination;

class JobsList extends Component
{
    use WithPagination;

    protected $listeners = [
        'echo:jobs,JobListUpdateEvent' => '$refresh'
    ];

    public function render()
    {
        $pendingJobs = Job::orderBy('created_at', 'asc')->paginate(10);

        return view('livewire.jobs-list', [
            'pendingJobs' => $pendingJobs
        ]);
    }
}
