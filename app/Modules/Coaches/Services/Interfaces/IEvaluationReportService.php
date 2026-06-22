<?php

namespace App\Modules\Coaches\Services\Interfaces;

interface IEvaluationReportService
{
    public function finalizeReport(array $data);

    public function getReportByEvaluationAndPlayer(int $evaluationId, int $playerId);

    public function getReportsListForPlayer(int $playerId);
}
