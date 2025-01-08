<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class CompletedJob extends Model
{
    protected $table = 'completed_jobs';

    protected $dates = [
        'created_at',
        'reserved_at',
        'completed_at'
    ];

    protected $fillable = ['project_id', 'client_name', 'created_at', 'reserved_at', 'completed_at', 'output_filename'];

    public function existsResultFile()
    {
        $filePath = storage_path('app/projects/' . $this->project_id . '/' . $this->output_filename);
        return File::exists($filePath);
    }
}
