<?php

namespace App\Models;

use App\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use HasFactory, SoftDeletes, Searchable;
    protected $table = 'announcements';
    protected $fillable = [
        'title',
        'content',
        'image',
        'link',
        'is_active',
        'published_at',
        'created_by',
    ];

    protected $searchable = [
        'title',
        'content',
        'link',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
