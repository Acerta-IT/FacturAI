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

    protected $appends = ['downloadUrl'];

    public function getDownloadUrlAttribute()
    {
        $filePath = public_path('downloads/' . $this->output_filename);
        return File::exists($filePath) ? 'downloads/' . $this->output_filename : null;
    }
}
