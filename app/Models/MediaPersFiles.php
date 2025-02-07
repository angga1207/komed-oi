<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaPersFiles extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'pers_profile_files';

    function MediaPers()
    {
        return $this->belongsTo(MediaPers::class, 'pers_profile_id', 'id');
    }
}
