<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\PairwiseCriteria;
use App\Modules\Coaches\Repositories\Interfaces\IPairwiseCriteriaRepository;

class PairwiseCriteriaRepository implements IPairwiseCriteriaRepository
{
    public function getCriteriaByGroup(
        int $groupId
    ) {
        return Criteria::whereHas('criteriaSet', function ($q) use ($groupId) {
            $q->where('group_id', $groupId);
        })
            ->orderBy('id')
            ->get();
    }

    public function deleteByPosition(
        int $positionId,
        ?int $pairwiseSetId = null
    ) {
        $query = PairwiseCriteria::where('position_id', $positionId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->delete();
    }

    public function insertMany(
        array $data
    ) {
        return PairwiseCriteria::insert(
            $data
        );
    }

    public function getByPosition(
        int $positionId,
        ?int $pairwiseSetId = null
    ) {
        $query = PairwiseCriteria::where('position_id', $positionId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->with([
            'firstCriteria',
            'secondCriteria',
        ])->get();
    }

    public function getValue(
        int $positionId,
        int $firstId,
        int $secondId,
        ?int $pairwiseSetId = null
    ) {
        $query = PairwiseCriteria::where('position_id', $positionId)
            ->where('criteria_first_id', $firstId)
            ->where('criteria_second_id', $secondId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->first();
    }

    public function saveValue(
        int $positionId,
        int $firstId,
        int $secondId,
        float $value,
        ?int $pairwiseSetId = null
    ) {
        $query = PairwiseCriteria::where('position_id', $positionId)
            ->where('criteria_first_id', $firstId)
            ->where('criteria_second_id', $secondId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->update([
            'value' => $value,
        ]);
    }

    public function getPairwise(
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    ) {
        $query = PairwiseCriteria::with([
            'firstCriteria',
            'secondCriteria',
        ])
            ->where('position_id', $positionId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->whereHas('firstCriteria.criteriaSet', function ($query) use ($groupId) {
            $query->where('group_id', $groupId);
        })
            ->whereHas('secondCriteria.criteriaSet', function ($query) use ($groupId) {
                $query->where('group_id', $groupId);
            })
            ->orderBy('criteria_first_id')
            ->orderBy('criteria_second_id')
            ->get();
    }
}
