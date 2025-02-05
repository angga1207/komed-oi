<?php

namespace App\Models;

use App\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaPers extends Model
{
    use HasFactory, Searchable;
    protected $guarded = [];
    protected $table = 'pers_profile';

    protected $searchable = [
        'unique_id',
        'nama_perusahaan',
        'nama_media',
        'alias',
        'jenis_media',
        'alamat_media',
        'whatsapp',
        'email',
        'User.fullname',
    ];

    function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
