<?php

namespace App\Modules\Coaches\Models;

use App\Modules\Admin\Models\Coach;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'title',
        'date',
        'coach_id',
        'pairwise_set_id',
        'status',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }

    public function pairwiseSet()
    {
        return $this->belongsTo(
            PairwiseSet::class,
            'pairwise_set_id'
        );
    }
}
