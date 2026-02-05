<?php

namespace App\Models;

use App\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderEvidence extends Model
{
    use HasFactory, SoftDeletes, Searchable;
    protected $table = 'order_evidences';
    protected $guarded = [];
    protected $searchable = [
        'MediaPers.nama_media',
        'MediaPers.nama_perusahaan',
        'Order.order_code',
        'Order.nama_acara',
        'Agenda.data',
        'Agenda.nama_acara',
        'Agenda.lokasi',
        'description',
    ];

    function Order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    function MediaPers()
    {
        return $this->belongsTo(MediaPers::class, 'media_id', 'id');
    }

    function Agenda()
    {
        return $this->belongsTo(Agenda::class, 'agenda_id', 'id');
    }
}
