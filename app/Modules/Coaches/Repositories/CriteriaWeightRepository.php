<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\CriteriaWeight;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaWeightRepository;

class CriteriaWeightRepository implements ICriteriaWeightRepository
{
    public function updateOrCreate(
        int $positionId,
        int $criteriaId,
        float $weight,
        ?int $pairwiseSetId = null
    ) {
        return CriteriaWeight::updateOrCreate(
            [
                'position_id' => $positionId,
                'criteria_id' => $criteriaId,
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
        $query = CriteriaWeight::where('position_id', $positionId);

        if ($pairwiseSetId !== null) {
            $query->where('pairwise_set_id', $pairwiseSetId);
        } else {
            $query->whereNull('pairwise_set_id');
        }

        return $query->with('criteria')->get();
    }
}
