<?php

namespace App\Modules\Coaches\Models;

use App\Modules\Admin\Models\Group;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairwiseSet extends Model
{
    use HasFactory;

    protected $table = 'pairwise_sets';

    protected $fillable = [
        'name',
        'group_id',
        'criteria_set_id',
    ];

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function criteriaSet()
    {
        return $this->belongsTo(CriteriaSet::class, 'criteria_set_id');
    }

    public function pairwiseCriteria()
    {
        return $this->hasMany(PairwiseCriteria::class, 'pairwise_set_id');
    }

    public function pairwiseSubCriteria()
    {
        return $this->hasMany(PairwiseSubCriteria::class, 'pairwise_set_id');
    }

    public function criteriaWeights()
    {
        return $this->hasMany(CriteriaWeight::class, 'pairwise_set_id');
    }

    public function subCriteriaWeights()
    {
        return $this->hasMany(SubCriteriaWeight::class, 'pairwise_set_id');
    }
}
