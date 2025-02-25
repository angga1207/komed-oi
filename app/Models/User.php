<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Searchable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, Searchable;
    protected $fillable = [
        'fullname',
        'first_name',
        'last_name',
        'username',
        'email',
        'photo',
        'google_id',
        'status',
        'role_id',
        'password',
    ];

    protected $searchable = [
        'fullname',
        'first_name',
        'last_name',
        'username',
        'email',
        // 'Role.name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    function Role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    function getLogs()
    {
        return $this->hasMany(UserLogs::class, 'user_id', 'id');
    }

    // public function routeNotificationForFcm()
    // {
    //     return $this->getDeviceTokens();
    // }

    function getMedia()
    {
        return $this->hasOne(MediaPers::class, 'user_id', 'id');
    }

    public function routeNotificationForFcm()
    {
        $tokens = DB::table('firebase_tokens')
            ->where('user_id', $this->id)
            ->where('type', 'mobile')
            ->first()->token ?? null;
        // ->pluck('token')
        // ->toArray();
        return $tokens;
        // return $tokens[0];
        // return $this->fcm_token;
    }

    function announcements()
    {
        return $this->hasMany(Announcement::class, 'created_by', 'id');
    }
}
