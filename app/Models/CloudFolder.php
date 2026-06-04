<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CloudFolder extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'parent_id',
        'name',
    ];

    public function parent()
    {
        return $this->belongsTo(CloudFolder::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(CloudFolder::class, 'parent_id');
    }

    public function files()
    {
        return $this->hasMany(CloudFile::class, 'folder_id');
    }
}