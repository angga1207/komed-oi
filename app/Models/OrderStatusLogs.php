<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderStatusLogs extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'log_order_status';
    protected $guarded = [];

    function Order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    function Agenda()
    {
        return $this->belongsTo(Agenda::class, 'agenda_id', 'id');
    }

    function MediaPers()
    {
        return $this->belongsTo(MediaPers::class, 'media_id', 'id');
    }
}
