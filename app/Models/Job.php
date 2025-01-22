<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class Job extends Model
{
    protected $table = 'jobs';

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
        'available_at' => 'datetime',
        'reserved_at' => 'datetime'
    ];

    protected $appends = ['clientName', 'processing', 'projectId', 'progress', 'converting_html'];

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
        return !is_null($this->reserved_at) && is_null($this->completed_at);
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

    public function getProgressAttribute()
    {
        if ($this->processing) {
            try {
                $key_current = "job:{$this->projectId}:current";
                $key_total = "job:{$this->projectId}:total";
                $key_converting_html = "job:{$this->projectId}:converting_html";
                // Get raw values
                $redis = Redis::connection('default');
                $current_raw = $redis->get($key_current);
                $total_raw = $redis->get($key_total);
                $converting_html_raw = $redis->get($key_converting_html);
                // Convert to integers, handling null values
                $current = $current_raw !== null ? (int)$current_raw : 0;
                $total = $total_raw !== null ? (int)$total_raw : 1;
                $converting_html = $converting_html_raw !== null ? (bool)$converting_html_raw : false;
                return [
                    'current' => $current,
                    'total' => $total,
                    'percentage' => $total > 0 ? min(($current / $total) * 100, 100) : 0,
                    'converting_html' => $converting_html
                ];
            } catch (\Exception $e) {
                Log::error('Redis error: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString()
                ]);
                return null;
            }
        }
        return null;
    }
}
