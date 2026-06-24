<?php

namespace App\Modules\Coaches\Models;

use App\Modules\Admin\Models\Player;
use Illuminate\Database\Eloquent\Model;

class EvaluationScore extends Model
{
    protected $fillable = [
        'evaluation_id',
        'player_id',
        'sub_criteria_id',
        'score',
    ];

    public function player()
    {
        return $this->belongsTo(
            Player::class,
            'player_id'
        );
    }

    public function evaluation()
    {
        return $this->belongsTo(
            Evaluation::class,
            'evaluation_id'
        );
    }

    public function subCriteria()
    {
        return $this->belongsTo(
            SubCriteria::class,
            'sub_criteria_id'
        );
    }
}
