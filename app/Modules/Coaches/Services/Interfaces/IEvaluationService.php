<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IEvaluationService
{
    public function createEvaluation(array $data, int $coachId);
}