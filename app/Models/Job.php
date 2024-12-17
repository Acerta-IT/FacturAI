<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Job extends Model
{
    protected $table = 'jobs';

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'datetime',
        'available_at' => 'datetime',
        'reserved_at' => 'datetime'
    ];

    protected $appends = ['clientName', 'processing'];

    public function getClientNameAttribute()
    {
        $command = unserialize($this->payload['data']['command']);
        return $command->clientName;
    }

    public function getProcessingAttribute()
    {
        return !is_null($this->reserved_at);
    }
}
