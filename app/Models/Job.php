<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Job extends Model
{
    protected $table = 'jobs';

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
        'available_at' => 'datetime',
        'reserved_at' => 'datetime'
    ];

    protected $appends = ['clientName', 'processing', 'projectId'];

    public function getClientNameAttribute()
    {
        try {
            $command = unserialize($this->payload['data']['command']);
            return $command->clientName ?? 'Unknown Client';
        } catch (\Exception $e) {
            Log::error('Error getting client name:', [
                'error' => $e->getMessage(),
                'payload' => $this->payload
            ]);
            return 'Unknown Client';
        }
    }

    public function getProcessingAttribute()
    {
        return !is_null($this->reserved_at);
    }

    public function getProjectIdAttribute()
    {
        try {
            $command = unserialize($this->payload['data']['command']);
            return $command->projectId ?? 'Unknown Project';
        } catch (\Exception $e) {
            Log::error('Error getting project id:', [
                'error' => $e->getMessage(),
                'payload' => $this->payload
            ]);
            return 'Unknown Project';
        }
    }
}
