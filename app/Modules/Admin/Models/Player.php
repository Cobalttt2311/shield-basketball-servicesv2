<?php

namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Models\User;

class Player extends Model
{
    protected $fillable = [
        'name',
        'birth_date',
        'group_id',
        'phone_number',
        'email',
        'height',
        'weight',
        'parent_name',
        'parent_phone',
        'user_id'
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