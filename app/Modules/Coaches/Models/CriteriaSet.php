<?php

namespace App\Modules\Coaches\Models;

use App\Modules\Admin\Models\Group;
use Illuminate\Database\Eloquent\Model;

class CriteriaSet extends Model
{
    protected $table = 'criteria_sets';

    protected $fillable = [
        'name',
        'group_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function criterias()
    {
        return $this->hasMany(Criteria::class, 'criteria_set_id');
    }
}
