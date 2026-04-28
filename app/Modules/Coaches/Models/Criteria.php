<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\Group;

class Criteria extends Model
{
    protected $table = 'criteria';
    protected $fillable = [
        'name',
        'group_id'
    ];

    public function subCriteria()
    {
        return $this->hasMany(SubCriteria::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}