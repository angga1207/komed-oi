<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'roles';
    protected $fillable = [
        'name',
        'slug',
    ];

    function Users()
    {
        return $this->hasMany(User::class, 'role_id', 'id');
    }
}
