<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class CriteriaWeight extends Model
{
    protected $table = 'criteria_weights';

    protected $fillable = [
        'position_id',
        'criteria_id',
        'weight'
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
}