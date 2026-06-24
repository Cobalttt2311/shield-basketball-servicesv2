<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface ISubCriteriaWeightRepository
{
    public function updateOrCreate(
        int $positionId,
        int $subCriteriaId,
        float $weight,
        ?int $pairwiseSetId = null
    );

    public function getByPosition(
        int $positionId,
        ?int $pairwiseSetId = null
    );
}
