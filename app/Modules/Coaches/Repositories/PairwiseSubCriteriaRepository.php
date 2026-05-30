<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Models\PairwiseSubCriteria;

class PairwiseSubCriteriaRepository
{
    public function getSubCriteria(
        $criteriaId
    ) {
        return SubCriteria::where(
            'criteria_id',
            $criteriaId
        )->get();
    }

    public function truncate(
        $criteriaId
    ) {
        return PairwiseSubCriteria::where(
            'criteria_id',
            $criteriaId
        )->delete();
    }

    public function insertMany($data)
    {
        return PairwiseSubCriteria::insert($data);
    }

    public function updateValue(
        $criteriaId,
        $firstId,
        $secondId,
        $value
    ) {
        return PairwiseSubCriteria::where(
            'criteria_id',
            $criteriaId
        )->where(
            'sub_criteria_first_id',
            $firstId
        )->where(
            'sub_criteria_second_id',
            $secondId
        )->update([
            'value' => $value
        ]);
    }

    public function getValue(
        $criteriaId,
        $firstId,
        $secondId
    ) {
        return PairwiseSubCriteria::where(
            'criteria_id',
            $criteriaId
        )->where(
            'sub_criteria_first_id',
            $firstId
        )->where(
            'sub_criteria_second_id',
            $secondId
        )->first();
    }
}