<?php

namespace App\Modules\Coaches\Repositories\Interfaces;

interface IEvaluationReportRepository
{
    public function saveReport(array $data);

    public function getReportByEvaluationAndPlayer(int $evaluationId, int $playerId);

    public function hasReport(int $evaluationId, int $playerId): bool;

    public function getReportsByPlayer(int $playerId);
}
