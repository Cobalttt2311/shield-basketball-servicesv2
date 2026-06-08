<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class SubCriteria extends Model
{
    protected $table = 'sub_criteria';

    protected $fillable = [
        'criteria_id',
        'name'
    ];

    public function criteria()
    {
        return $this->belongsTo(
            Criteria::class
        );
    }

    public function firstPairwise()
    {
        return $this->hasMany(
            PairwiseSubCriteria::class,
            'sub_criteria_first_id'
        );
    }

    public function secondPairwise()
    {
        return $this->hasMany(
            PairwiseSubCriteria::class,
            'sub_criteria_second_id'
        );
    }

    public function weights()
    {
        return $this->hasMany(
            SubCriteriaWeight::class
        );
    }
}