<?php

namespace App\Models;

use App\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Agenda extends Model
{
    use HasFactory, SoftDeletes, Searchable;
    protected $table = 'agendas';
    protected $guarded = [];

    protected $searchable = [
        'nama_acara',
        'lokasi',
    ];
}
