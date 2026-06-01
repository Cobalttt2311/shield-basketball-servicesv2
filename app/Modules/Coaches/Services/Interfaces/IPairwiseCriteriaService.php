<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IPairwiseCriteriaService
{
    public function generatePairwise(
        int $groupId,
        int $positionId
    );

    public function updateValue(
        array $data
    );

    public function generateMatrix(
        int $groupId,
        int $positionId
    );

    public function calculateWeights(
        int $groupId,
        int $positionId
    );

    public function saveWeights(
        int $groupId,
        int $positionId
    );

    public function calculateConsistencyRatio(
        int $groupId,
        int $positionId
    );
}