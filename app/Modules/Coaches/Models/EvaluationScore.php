<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class EvaluationScore extends Model
{
    protected $fillable = [
        'evaluation_id',
        'player_id',
        'sub_criteria_id',
        'score'
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}