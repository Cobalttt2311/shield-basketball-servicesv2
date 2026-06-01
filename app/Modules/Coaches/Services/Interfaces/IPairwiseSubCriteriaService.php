<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IPairwiseSubCriteriaService
{
    public function generatePairwise(
        int $positionId,
        int $criteriaId
    );

    public function updateValue(
        array $data
    );

    public function generateMatrix(
        int $positionId,
        int $criteriaId
    );

    public function calculateWeights(
        int $positionId,
        int $criteriaId
    );

    public function saveWeights(
        int $positionId,
        int $criteriaId
    );

    public function calculateConsistencyRatio(
        int $positionId,
        int $criteriaId
    );
}