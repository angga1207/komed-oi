<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLogs extends Model
{
    protected $table = 'user_logs';

    protected $fillable = [
        'user_id',
        'action',
        'model',
        'endpoint',
        'payload',
        'message'
    ];

    function User()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
