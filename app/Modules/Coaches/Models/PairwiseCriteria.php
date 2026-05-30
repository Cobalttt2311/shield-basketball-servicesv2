<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class PairwiseCriteria extends Model
{
    protected $table = 'pairwise_criteria';

    protected $fillable = [
        'group_id',
        'criteria_first_id',
        'criteria_second_id',
        'value'
    ];

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
}