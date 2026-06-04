<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CloudFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'folder_id',
        'original_name',
        'stored_name',
        'path',
        'mime_type',
        'size',
        'version',
    ];

    public function folder()
    {
        return $this->belongsTo(CloudFolder::class, 'folder_id');
    }

    public function versions()
    {
        return $this->hasMany(FileVersion::class);
    }
}