<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class PairwiseSubCriteria extends Model
{
    protected $table = 'pairwise_sub_criteria';

    protected $fillable = [
        'criteria_id',
        'sub_criteria_first_id',
        'sub_criteria_second_id',
        'value'
    ];

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

    public function criteria()
    {
        return $this->belongsTo(Criteria::class);
    }
}