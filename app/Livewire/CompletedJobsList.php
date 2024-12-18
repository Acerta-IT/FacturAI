<?php

namespace App\Livewire;

use App\Models\CompletedJob;
use Livewire\Component;
use Livewire\WithPagination;

class CompletedJobsList extends Component
{
    use WithPagination;

    protected $listeners = [
        'echo:completed-jobs,CompletedJobListUpdateEvent' => '$refresh'
    ];

    public function render()
    {
        return view('livewire.completed-jobs-list', [
            'completedJobs' => CompletedJob::orderBy('completed_at', 'desc')->paginate(10)
        ]);
    }
}
