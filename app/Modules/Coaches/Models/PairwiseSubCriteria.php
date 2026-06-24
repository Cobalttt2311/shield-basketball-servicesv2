<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class PairwiseSubCriteria extends Model
{
    protected $table = 'pairwise_sub_criteria';

    protected $fillable = [
        'position_id',
        'criteria_id',
        'sub_criteria_first_id',
        'sub_criteria_second_id',
        'value',
        'pairwise_set_id',
    ];

    public function position()
    {
        return $this->belongsTo(
            Position::class
        );
    }

    public function criteria()
    {
        return $this->belongsTo(
            Criteria::class
        );
    }

    public function firstSubCriteria()
    {
        return $this->belongsTo(
            SubCriteria::class,
            'sub_criteria_first_id'
        );
    }

    public function secondSubCriteria()
    {
        return $this->belongsTo(
            SubCriteria::class,
            'sub_criteria_second_id'
        );
    }

    public function pairwiseSet()
    {
        return $this->belongsTo(
            PairwiseSet::class,
            'pairwise_set_id'
        );
    }
}
