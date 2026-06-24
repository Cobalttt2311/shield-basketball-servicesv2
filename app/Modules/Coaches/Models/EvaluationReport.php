<?php

namespace App\Modules\Coaches\Models;

use App\Modules\Admin\Models\Player;
use Illuminate\Database\Eloquent\Model;

class EvaluationReport extends Model
{
    protected $table = 'evaluation_reports';

    protected $fillable = [
        'evaluation_id',
        'player_id',
        'recommended_position_id',
        'final_position_id',
        'notes',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    public function player()
    {
        return $this->belongsTo(Player::class, 'player_id');
    }

    public function recommendedPosition()
    {
        return $this->belongsTo(Position::class, 'recommended_position_id');
    }

    public function finalPosition()
    {
        return $this->belongsTo(Position::class, 'final_position_id');
    }
}
