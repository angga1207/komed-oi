<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'orders';
    protected $guarded = [];

    function Agenda()
    {
        return $this->belongsTo(Agenda::class, 'agenda_id', 'id');
    }

    function MediaPers()
    {
        return $this->belongsTo(MediaPers::class, 'media_id', 'id');
    }

    function StatusLogs()
    {
        return $this->hasMany(OrderStatusLogs::class, 'id', 'media_id');
    }
}
