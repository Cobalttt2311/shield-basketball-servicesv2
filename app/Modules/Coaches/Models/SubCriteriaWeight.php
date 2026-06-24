<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class SubCriteriaWeight extends Model
{
    protected $table = 'sub_criteria_weights';

    protected $fillable = [
        'position_id',
        'sub_criteria_id',
        'weight',
        'pairwise_set_id',
    ];

    public function position()
    {
        return $this->belongsTo(
            Position::class
        );
    }

    public function subCriteria()
    {
        return $this->belongsTo(
            SubCriteria::class
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
