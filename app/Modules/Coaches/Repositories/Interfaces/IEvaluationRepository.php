<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IEvaluationRepository
{
    public function findSubCriteriaWithCriteria(int $id);

    public function createEvaluation(array $data);

    public function insertScores(array $scores);

    public function getAllEvaluations();

    public function getEvaluationById(int $id);

    public function updateEvaluation(int $id, array $data);

    public function deleteEvaluation(int $id);
    public function deleteScoresByEvaluation(int $evaluationId);
}