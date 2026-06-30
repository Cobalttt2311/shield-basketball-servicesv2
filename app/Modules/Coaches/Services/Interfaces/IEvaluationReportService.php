<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IEvaluationReportService
{
    public function finalizeReport(array $data);

    public function getReportByEvaluationAndPlayer(int $evaluationId, int $playerId, bool $allowDraft = false);

    public function getReportsListForPlayer(int $playerId);

    public function getPlayersForFinalization(int $evaluationId): array;

    public function saveRecommendationDrafts(int $evaluationId, array $recommendations);

    public function generatePdfReport(int $evaluationId, int $playerId);
}
