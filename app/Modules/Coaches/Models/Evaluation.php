<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Admin\Models\Coach;

class Evaluation extends Model
{
    protected $fillable = [
        'title',
        'date',
        'coach_id'
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function scores()
    {
        return $this->hasMany(EvaluationScore::class);
    }
}