<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\EvaluationScore;
use App\Modules\Coaches\Repositories\Interfaces\IPlayerScoreMappingRepository;

class PlayerScoreMappingRepository
    implements IPlayerScoreMappingRepository
{
    public function getScoresBySubCriteria(
        int $evaluationId,
        int $subCriteriaId
    ) {
        return EvaluationScore::with('player')
            ->where(
                'evaluation_id',
                $evaluationId
            )
            ->where(
                'sub_criteria_id',
                $subCriteriaId
            )
            ->get();
    }
}