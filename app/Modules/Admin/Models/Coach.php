<?php

namespace App\Modules\Admin\Models;

use App\Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    protected $fillable = [
        'name',
        'birth_date',
        'group_id',
        'position',
        'license',
        'phone_number',
        'email',
        'user_id',
        'is_master',
    ];

    protected $casts = [
        'is_master' => 'boolean',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
