<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\SubCriteriaWeight;
use App\Modules\Coaches\Repositories\Interfaces\ISubCriteriaWeightRepository;

class SubCriteriaWeightRepository implements ISubCriteriaWeightRepository
{
    public function updateOrCreate(
        int $positionId,
        int $subCriteriaId,
        float $weight
    ) {
        return SubCriteriaWeight::updateOrCreate(
            [
                'position_id' => $positionId,
                'sub_criteria_id' => $subCriteriaId,
            ],
            [
                'weight' => $weight,
            ]
        );
    }

    public function getByPosition(
        int $positionId
    ) {
        return SubCriteriaWeight::where(
            'position_id',
            $positionId
        )
            ->with('subCriteria')
            ->get();
    }
}
