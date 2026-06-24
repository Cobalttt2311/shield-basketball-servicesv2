<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IPlayerScoreMappingRepository
{
    public function getScoresBySubCriteria(
        int $evaluationId,
        int $subCriteriaId
    );
}
