<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IPairwiseSubCriteriaService
{
    public function generatePairwise(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    );

    public function saveValue(
        array $data,
        ?int $pairwiseSetId = null
    ): void;

    public function generateMatrix(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    );

    public function calculateWeights(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    );

    public function saveWeights(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    );

    public function calculateConsistencyRatio(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    );

    public function getPairwise(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    );
}
