<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class PairwiseCriteria extends Model
{
    protected $table = 'pairwise_criteria';

    protected $fillable = [
        'position_id',
        'criteria_first_id',
        'criteria_second_id',
        'value',
        'pairwise_set_id',
    ];

    public function position()
    {
        return $this->belongsTo(
            Position::class
        );
    }

    public function firstCriteria()
    {
        return $this->belongsTo(
            Criteria::class,
            'criteria_first_id'
        );
    }

    public function secondCriteria()
    {
        return $this->belongsTo(
            Criteria::class,
            'criteria_second_id'
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
