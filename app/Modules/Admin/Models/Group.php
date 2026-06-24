<?php

namespace App\Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $fillable = ['age_group'];

    public function coaches()
    {
        return $this->hasMany(Coach::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }
}
