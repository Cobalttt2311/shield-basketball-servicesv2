<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IPairwiseCriteriaService
{
    public function generatePairwise(
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    );

    public function saveValue(
        array $data,
        ?int $pairwiseSetId = null
    ): void;

    public function generateMatrix(
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    );

    public function calculateWeights(
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    );

    public function saveWeights(
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    );

    public function calculateConsistencyRatio(
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    );

    public function getPairwise(
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    );
}
