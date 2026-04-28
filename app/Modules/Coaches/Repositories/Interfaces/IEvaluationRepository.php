<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IEvaluationRepository
{
    public function findSubCriteriaWithCriteria(int $id);

    public function createEvaluation(array $data);

    public function insertScores(array $scores);
}