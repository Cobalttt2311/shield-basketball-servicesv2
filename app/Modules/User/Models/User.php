<?php

namespace App\Modules\User\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Player;
use Illuminate\Auth\Passwords\CanResetPassword;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, CanResetPassword;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function coach()
    {
        return $this->hasOne(Coach::class, 'user_id');
    }

    public function player()
    {
        return $this->hasOne(Player::class, 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'role' => $this->role,
        ];
    }
}