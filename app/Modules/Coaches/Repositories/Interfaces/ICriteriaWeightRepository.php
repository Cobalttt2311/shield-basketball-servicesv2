<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface ICriteriaWeightRepository
{
    public function updateOrCreate(
        int $positionId,
        int $criteriaId,
        float $weight,
        ?int $pairwiseSetId = null
    );

    public function getByPosition(
        int $positionId,
        ?int $pairwiseSetId = null
    );
}
