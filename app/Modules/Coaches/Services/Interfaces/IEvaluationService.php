<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IEvaluationService
{
    public function createEvaluation(array $data, int $coachId);

    public function getAllEvaluations();

    public function getEvaluationById(int $id);

    public function updateEvaluation(int $id, array $data);

    public function deleteEvaluation(int $id);
}