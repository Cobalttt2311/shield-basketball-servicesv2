<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class SubCriteria extends Model
{
    protected $table = 'sub_criteria';
    protected $fillable = [
        'criteria_id',
        'name',
        'group_id'
    ];

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}