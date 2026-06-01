<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\PairwiseCriteria;

class PairwiseCriteriaRepository
{
    public function getCriteriaByGroup(
        int $groupId
    )
    {
        return Criteria::where(
            'group_id',
            $groupId
        )
        ->orderBy('id')
        ->get();
    }

    public function deleteByPosition(
        int $positionId
    )
    {
        return PairwiseCriteria::where(
            'position_id',
            $positionId
        )->delete();
    }

    public function insertMany(
        array $data
    )
    {
        return PairwiseCriteria::insert(
            $data
        );
    }

    public function getByPosition(
        int $positionId
    )
    {
        return PairwiseCriteria::where(
            'position_id',
            $positionId
        )
        ->with([
            'firstCriteria',
            'secondCriteria'
        ])
        ->get();
    }

    public function getValue(
        int $positionId,
        int $firstId,
        int $secondId
    )
    {
        return PairwiseCriteria::where(
            'position_id',
            $positionId
        )
        ->where(
            'criteria_first_id',
            $firstId
        )
        ->where(
            'criteria_second_id',
            $secondId
        )
        ->first();
    }

    public function updateValue(
        int $positionId,
        int $firstId,
        int $secondId,
        float $value
    )
    {
        return PairwiseCriteria::where(
            'position_id',
            $positionId
        )
        ->where(
            'criteria_first_id',
            $firstId
        )
        ->where(
            'criteria_second_id',
            $secondId
        )
        ->update([
            'value' => $value
        ]);
    }
}