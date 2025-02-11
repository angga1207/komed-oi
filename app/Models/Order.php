<?php

namespace App\Models;

use App\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory, SoftDeletes, Searchable;
    protected $table = 'orders';
    protected $guarded = [];
    protected $searchable = [
        'order_code',
        'nama_acara',
        'Agenda.data',
        'leading_sector',
        'tanggal_pelaksanaan',
    ];

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
