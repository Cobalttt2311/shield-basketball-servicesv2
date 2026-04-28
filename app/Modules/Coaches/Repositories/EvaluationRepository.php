<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\Evaluation;
use App\Modules\Coaches\Models\EvaluationScore;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Repositories\Interfaces\IEvaluationRepository;

class EvaluationRepository implements IEvaluationRepository
{
    public function findSubCriteriaWithCriteria(int $id)
    {
        return SubCriteria::with('criteria')->find($id);
    }

    public function createEvaluation(array $data)
    {
        return Evaluation::create($data);
    }

    public function insertScores(array $scores)
    {
        return EvaluationScore::insert($scores);
    }
}