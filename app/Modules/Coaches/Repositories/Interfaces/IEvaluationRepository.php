<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IEvaluationRepository
{
    public function createEvaluation(array $data);
    public function insertScores(array $scores);
}