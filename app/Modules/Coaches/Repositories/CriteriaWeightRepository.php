<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\CriteriaWeight;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaWeightRepository;

class CriteriaWeightRepository implements ICriteriaWeightRepository
{
    public function updateOrCreate(
        int $positionId,
        int $criteriaId,
        float $weight
    ) {
        return CriteriaWeight::updateOrCreate(
            [
                'position_id' => $positionId,
                'criteria_id' => $criteriaId,
            ],
            [
                'weight' => $weight,
            ]
        );
    }

    public function getByPosition(
        int $positionId
    ) {
        return CriteriaWeight::where(
            'position_id',
            $positionId
        )
            ->with('criteria')
            ->get();
    }
}
