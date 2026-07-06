<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    protected $table = 'criteria';

    protected $fillable = [
        'criteria_set_id',
        'name',
    ];

    public function criteriaSet()
    {
        return $this->belongsTo(CriteriaSet::class, 'criteria_set_id');
    }

    public function subCriteria()
    {
        return $this->hasMany(
            SubCriteria::class
        );
    }

    public function firstPairwise()
    {
        return $this->hasMany(
            PairwiseCriteria::class,
            'criteria_first_id'
        );
    }

    public function secondPairwise()
    {
        return $this->hasMany(
            PairwiseCriteria::class,
            'criteria_second_id'
        );
    }

    public function weights()
    {
        return $this->hasMany(
            CriteriaWeight::class
        );
    }
}
