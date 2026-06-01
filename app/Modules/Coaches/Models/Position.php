<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'positions';

    protected $fillable = [
        'group_id',
        'name'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function pairwiseCriteria()
    {
        return $this->hasMany(
            PairwiseCriteria::class
        );
    }

    public function pairwiseSubCriteria()
    {
        return $this->hasMany(
            PairwiseSubCriteria::class
        );
    }

    public function criteriaWeights()
    {
        return $this->hasMany(
            CriteriaWeight::class
        );
    }

    public function subCriteriaWeights()
    {
        return $this->hasMany(
            SubCriteriaWeight::class
        );
    }
}