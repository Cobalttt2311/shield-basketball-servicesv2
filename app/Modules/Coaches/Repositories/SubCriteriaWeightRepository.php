<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\SubCriteriaWeight;
use App\Modules\Coaches\Repositories\Interfaces\ISubCriteriaWeightRepository;

class SubCriteriaWeightRepository implements ISubCriteriaWeightRepository
{
    public function updateOrCreate(
        int $positionId,
        int $subCriteriaId,
        float $weight,
        ?int $pairwiseSetId = null
    ) {
        return SubCriteriaWeight::updateOrCreate(
            [
                'position_id' => $positionId,
                'sub_criteria_id' => $subCriteriaId,
                'pairwise_set_id' => $pairwiseSetId,
            ],
            [
                'weight' => $weight,
            ]
        );
    }

    public function getByPosition(
        int $positionId,
        ?int $pairwiseSetId = null
    ) {
        $query = SubCriteriaWeight::where('position_id', $positionId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->with('subCriteria')->get();
    }
}
