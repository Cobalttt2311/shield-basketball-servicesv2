<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IPlayerScoreMappingService
{
    public function calculateAlternativeWeights(
        int $evaluationId,
        int $subCriteriaId
    );

    public function calculateAlternativeScores(
        int $evaluationId,
        int $positionId,
        ?int $pairwiseSetId = null
    );

    public function getPositionRecommendations(
        int $evaluationId
    );
}
