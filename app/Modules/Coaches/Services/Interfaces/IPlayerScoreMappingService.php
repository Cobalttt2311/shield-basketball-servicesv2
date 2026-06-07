<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IPlayerScoreMappingService
{
    public function calculateAlternativeWeights(
        int $evaluationId,
        int $subCriteriaId
    );
}