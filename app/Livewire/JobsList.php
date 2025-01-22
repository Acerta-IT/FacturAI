<?php

namespace App\Livewire;

use App\Models\Job;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;


class JobsList extends Component
{
    use WithPagination;

    public $jobToDeleteId = null;
    public $showDeleteModal = false;

    protected $listeners = [
        'echo:jobs,JobListUpdateEvent' => '$refresh',
        'echo:jobs,JobProcessingEvent' => '$refresh'
    ];

    private function getJobProgress($projectId)
    {
        $redis = Redis::connection('default');
        $key_current = "job:{$projectId}:current";
        $key_total = "job:{$projectId}:total";
        $key_converting_html = "job:{$projectId}:converting_html";

        $current_raw = $redis->get($key_current);
        $total_raw = $redis->get($key_total);
        $converting_html_raw = $redis->get($key_converting_html);

        $current = (int)($current_raw ?? 0);
        $total = (int)($total_raw ?? 1);
        $converting_html = (bool)($converting_html_raw ?? false);
        return [
            'current' => $current,
            'total' => $total,
            'percentage' => min(($current / $total) * 100, 100),
            'converting_html' => $converting_html
        ];
    }

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
        $pendingJobs = Job::orderBy('created_at', 'asc')
            ->paginate(10);

        return view('livewire.jobs-list', [
            'pendingJobs' => $pendingJobs
        ]);
    }
}
