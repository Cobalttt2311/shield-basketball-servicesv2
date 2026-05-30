<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\PairwiseCriteria;

class PairwiseCriteriaRepository
{
    public function getCriteriaByGroup($groupId)
    {
        return Criteria::where(
            'group_id',
            $groupId
        )->get();
    }

    public function truncateByGroup($groupId)
    {
        return PairwiseCriteria::where(
            'group_id',
            $groupId
        )->delete();
    }

    public function insertMany($data)
    {
        return PairwiseCriteria::insert($data);
    }

    public function updateValue(
        $groupId,
        $firstId,
        $secondId,
        $value
    ) {
        return PairwiseCriteria::where(
            'group_id',
            $groupId
        )->where(
            'criteria_first_id',
            $firstId
        )->where(
            'criteria_second_id',
            $secondId
        )->update([
            'value' => $value
        ]);
    }

    public function getValue(
        $groupId,
        $firstId,
        $secondId
    ) {
        return PairwiseCriteria::where(
            'group_id',
            $groupId
        )->where(
            'criteria_first_id',
            $firstId
        )->where(
            'criteria_second_id',
            $secondId
        )->first();
    }

    public function getAllByGroup($groupId)
    {
        return PairwiseCriteria::where(
            'group_id',
            $groupId
        )->get();
    }
}