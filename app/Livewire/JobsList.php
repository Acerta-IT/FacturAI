<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Job;

class JobsList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.jobs-list', [
            'pendingJobs' => Job::where('reserved_at', null)
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
    }
}
