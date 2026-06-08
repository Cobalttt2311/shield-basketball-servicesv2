<?php

namespace App\Modules\Coaches\Models;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Models\PairwiseCriteria;
use App\Modules\Coaches\Models\CriteriaWeight;
use App\Modules\Admin\Models\Group;

class Criteria extends Model
{
    protected $table = 'criteria';

    protected $fillable = [
        'group_id',
        'name'
    ];

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function subCriteria()
    {
        return $this->hasMany(
            SubCriteria::class
        );
    }

    public function firstPairwise()
    {
        return $this->hasMany(
            PairwiseCriteria::class,
            'criteria_first_id'
        );
    }

    public function secondPairwise()
    {
        return $this->hasMany(
            PairwiseCriteria::class,
            'criteria_second_id'
        );
    }

    public function weights()
    {
        return $this->hasMany(
            CriteriaWeight::class
        );
    }
}