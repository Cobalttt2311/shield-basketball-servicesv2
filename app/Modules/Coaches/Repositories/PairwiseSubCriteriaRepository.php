<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\PairwiseSubCriteria;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Repositories\Interfaces\IPairwiseSubCriteriaRepository;

class PairwiseSubCriteriaRepository implements IPairwiseSubCriteriaRepository
{
    public function getSubCriteriaByCriteria(
        int $criteriaId
    ) {
        return SubCriteria::where(
            'criteria_id',
            $criteriaId
        )
            ->orderBy('id')
            ->get();
    }

    public function deleteByPositionAndCriteria(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    ) {
        $query = PairwiseSubCriteria::where('position_id', $positionId)
            ->where('criteria_id', $criteriaId);

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
        return PairwiseSubCriteria::insert(
            $data
        );
    }

    public function getValue(
        int $positionId,
        int $criteriaId,
        int $firstId,
        int $secondId,
        ?int $pairwiseSetId = null
    ) {
        $query = PairwiseSubCriteria::where('position_id', $positionId)
            ->where('criteria_id', $criteriaId)
            ->where('sub_criteria_first_id', $firstId)
            ->where('sub_criteria_second_id', $secondId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->first();
    }

    public function saveValue(
        int $positionId,
        int $criteriaId,
        int $firstId,
        int $secondId,
        float $value,
        ?int $pairwiseSetId = null
    ) {
        $query = PairwiseSubCriteria::where('position_id', $positionId)
            ->where('criteria_id', $criteriaId)
            ->where('sub_criteria_first_id', $firstId)
            ->where('sub_criteria_second_id', $secondId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->update([
            'value' => $value,
        ]);
    }

    public function getByPositionAndCriteria(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    ) {
        $query = PairwiseSubCriteria::where('position_id', $positionId)
            ->where('criteria_id', $criteriaId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->with([
            'firstSubCriteria',
            'secondSubCriteria',
        ])->get();
    }

    public function getPairwise(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    ) {
        $query = PairwiseSubCriteria::with([
            'firstSubCriteria',
            'secondSubCriteria',
        ])
            ->where('position_id', $positionId)
            ->where('criteria_id', $criteriaId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->orderBy('sub_criteria_first_id')
            ->orderBy('sub_criteria_second_id')
            ->get();
    }
}
