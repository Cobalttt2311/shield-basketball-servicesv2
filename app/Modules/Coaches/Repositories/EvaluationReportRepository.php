<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\EvaluationReport;
use App\Modules\Coaches\Repositories\Interfaces\IEvaluationReportRepository;

class EvaluationReportRepository implements IEvaluationReportRepository
{
    public function saveReport(array $data)
    {
        return EvaluationReport::updateOrCreate(
            [
                'evaluation_id' => $data['evaluation_id'],
                'player_id' => $data['player_id'],
            ],
            [
                'recommended_position_id' => $data['recommended_position_id'],
                'final_position_id' => $data['final_position_id'],
                'notes' => $data['notes'] ?? null,
                'is_finalized' => true,
            ]
        );
    }

    public function saveDraft(array $data)
    {
        return EvaluationReport::updateOrCreate(
            [
                'evaluation_id' => $data['evaluation_id'],
                'player_id' => $data['player_id'],
            ],
            [
                'recommended_position_id' => $data['recommended_position_id'],
                'final_position_id' => $data['final_position_id'],
                'notes' => $data['notes'] ?? null,
                'is_finalized' => $data['is_finalized'] ?? false,
            ]
        );
    }

    public function getReportByEvaluationAndPlayer(int $evaluationId, int $playerId)
    {
        return EvaluationReport::with([
            'evaluation.coach',
            'player.group',
            'recommendedPosition',
            'finalPosition',
        ])
            ->where('evaluation_id', $evaluationId)
            ->where('player_id', $playerId)
            ->first();
    }

    public function hasReport(int $evaluationId, int $playerId): bool
    {
        return EvaluationReport::where('evaluation_id', $evaluationId)
            ->where('player_id', $playerId)
            ->where('is_finalized', true)
            ->exists();
    }

    public function getReportsByPlayer(int $playerId)
    {
        return EvaluationReport::select('evaluation_reports.*')
            ->join('evaluations', 'evaluation_reports.evaluation_id', '=', 'evaluations.id')
            ->with(['evaluation', 'finalPosition'])
            ->where('player_id', $playerId)
            ->where('is_finalized', true)
            ->orderBy('evaluations.date', 'desc')
            ->get();
    }
}
