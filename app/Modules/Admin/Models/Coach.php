<?php

namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Models\User;

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