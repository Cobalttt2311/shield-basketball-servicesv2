<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;

class SubCriteriaWeight extends Model
{
    protected $table = 'sub_criteria_weights';

    protected $fillable = [
        'position_id',
        'sub_criteria_id',
        'weight'
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
}