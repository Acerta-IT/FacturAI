<?php

namespace App\Livewire;

use App\Models\Job;
use Livewire\Component;
use Livewire\WithPagination;

class JobsList extends Component
{
    use WithPagination;

    public $jobToDeleteId = null;
    public $showDeleteModal = false;

    protected $listeners = [
        'echo:jobs,JobListUpdateEvent' => '$refresh'
    ];

    public function setJobIdToDelete($id)
    {
        $this->jobToDeleteId = $id;
        $this->showDeleteModal = true;
    }

    public function closeModal()
    {
        $this->showDeleteModal = false;
    }

    public function deleteJob()
    {
        $job = Job::find($this->jobToDeleteId);

        if ($job && !$job->processing) {
            $job->delete();

            $this->dispatch('alertDispatched', [
                'message' => 'Trabajo eliminado correctamente',
                'class' => 'toast-success'
            ]);
        }

        $this->closeModal();
    }

    public function render()
    {
        $pendingJobs = Job::orderBy('created_at', 'asc')->paginate(10);

        return view('livewire.jobs-list', [
            'pendingJobs' => $pendingJobs
        ]);
    }
}
