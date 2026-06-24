<?php

namespace App\Modules\User\Models;

use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Player;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use CanResetPassword, HasFactory, Notifiable;

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

    protected $appends = [
        'roles',
    ];

    public function getRolesAttribute(): array
    {
        $roles = [$this->role];

        if ($this->role === 'coach') {
            $coach = $this->coach;
            if ($coach) {
                if ($coach->is_master) {
                    $roles[] = 'master_coach';
                }

                $position = strtolower(trim($coach->position));
                if ($position === 'head coach') {
                    $roles[] = 'head_coach';
                } elseif ($position === 'assistant_coach' || $position === 'assistant coach') {
                    $roles[] = 'assistant_coach';
                }
            }
        }

        return $roles;
    }

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
            'roles' => $this->roles,
        ];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
