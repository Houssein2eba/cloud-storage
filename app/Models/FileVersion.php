<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileVersion extends Model
{
    protected $fillable = [
        'cloud_file_id',
        'version',
        'path',
        'stored_name',
        'size',
        'mime_type',
    ];

    public function file()
    {
        return $this->belongsTo(CloudFile::class, 'cloud_file_id');
    }
}