<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MediaKontrak extends Model
{
    use HasFactory;
    protected $guarded = [];
    protected $table = 'pers_kontrak';

    function MediaPers()
    {
        return $this->belongsTo(MediaPers::class, 'pers_profile_id', 'id');
    }
}
