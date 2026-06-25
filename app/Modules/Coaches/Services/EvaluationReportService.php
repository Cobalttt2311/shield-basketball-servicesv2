<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Player;
use App\Modules\Coaches\Models\Evaluation;
use App\Modules\Coaches\Models\EvaluationScore;
use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Repositories\Interfaces\IEvaluationReportRepository;
use App\Modules\Coaches\Services\Interfaces\IEvaluationReportService;
use App\Modules\Coaches\Services\Interfaces\IPlayerScoreMappingService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use Exception;

class EvaluationReportService implements IEvaluationReportService
{
    protected IEvaluationReportRepository $repository;

    protected IPlayerScoreMappingService $playerScoreMappingService;

    public function __construct(
        IEvaluationReportRepository $repository,
        IPlayerScoreMappingService $playerScoreMappingService
    ) {
        $this->repository = $repository;
        $this->playerScoreMappingService = $playerScoreMappingService;
    }

    public function finalizeReport(array $data)
    {
        // 1. Validate inputs
        $evaluationExists = Evaluation::where('id', $data['evaluation_id'])->exists();
        if (! $evaluationExists) {
            throw new Exception(ErrorMessages::EVALUATION_NOT_FOUND);
        }

        $playerExists = Player::where('id', $data['player_id'])->exists();
        if (! $playerExists) {
            throw new Exception(ErrorMessages::PLAYER_NOT_FOUND);
        }

        $recPositionExists = Position::where('id', $data['recommended_position_id'])->exists();
        if (! $recPositionExists) {
            throw new Exception(ErrorMessages::POSITION_NOT_FOUND);
        }

        $finalPositionExists = Position::where('id', $data['final_position_id'])->exists();
        if (! $finalPositionExists) {
            throw new Exception(ErrorMessages::POSITION_NOT_FOUND);
        }

        // 2. Save
        return $this->repository->saveReport($data);
    }

    public function getReportByEvaluationAndPlayer(int $evaluationId, int $playerId, bool $allowDraft = false)
    {
        // 1. Fetch finalized or draft report
        $report = $this->repository->getReportByEvaluationAndPlayer($evaluationId, $playerId);

        // Jika ada laporan di database tetapi belum difinalisasi (baru draf dari Step 2)
        if ($report && ! $report->is_finalized) {
            if (! $allowDraft) {
                throw new Exception(ErrorMessages::REPORT_NOT_FOUND);
            }

            // Fetch evaluation scores secara dinamis
            $scores = EvaluationScore::with('subCriteria.criteria')
                ->where('evaluation_id', $evaluationId)
                ->where('player_id', $playerId)
                ->get()
                ->map(function ($item) {
                    return [
                        'sub_criteria_id' => $item->sub_criteria_id,
                        'sub_criteria_name' => $item->subCriteria->name,
                        'criteria_id' => $item->subCriteria->criteria_id,
                        'criteria_name' => $item->subCriteria->criteria->name,
                        'score' => $item->score,
                    ];
                });

            // Fetch Coaches
            $player = $report->player;
            $coaches = Coach::where('group_id', $player->group_id)->get();
            $headCoach = $coaches->first(function ($c) {
                return strtolower(trim($c->position)) === 'head coach';
            });
            $assistantCoach = $coaches->first(function ($c) {
                return strtolower(trim($c->position)) === 'assistant coach';
            });

            return [
                'report_id' => null, // null agar FE tahu belum difinalisasi secara resmi
                'evaluation_id' => $evaluationId,
                'evaluation_title' => $report->evaluation->title,
                'evaluation_date' => $report->evaluation->date,
                'player_id' => $playerId,
                'player_name' => $player->name,
                'group_name' => $player->group ? $player->group->age_group : null,
                'head_coach' => $headCoach ? $headCoach->name : null,
                'assistant_coach' => $assistantCoach ? $assistantCoach->name : null,
                'recommended_position_id' => $report->recommended_position_id,
                'recommended_position_name' => $report->recommendedPosition ? $report->recommendedPosition->name : null,
                'final_position_id' => null, // null agar FE tahu pelatih belum memilih final position secara resmi
                'final_position_name' => null,
                'notes' => null,
                'scores' => $scores,
            ];
        }

        if (! $report) {
            if (! $allowDraft) {
                throw new Exception(ErrorMessages::REPORT_NOT_FOUND);
            }

            // Check if player and evaluation exist to prevent returning draft for invalid IDs
            $player = Player::with('group')->find($playerId);
            $evaluation = Evaluation::find($evaluationId);
            if (! $player || ! $evaluation) {
                throw new Exception(ErrorMessages::REPORT_NOT_FOUND);
            }

            // Fetch evaluation scores
            $scores = EvaluationScore::with('subCriteria.criteria')
                ->where('evaluation_id', $evaluationId)
                ->where('player_id', $playerId)
                ->get()
                ->map(function ($item) {
                    return [
                        'sub_criteria_id' => $item->sub_criteria_id,
                        'sub_criteria_name' => $item->subCriteria->name,
                        'criteria_id' => $item->subCriteria->criteria_id,
                        'criteria_name' => $item->subCriteria->criteria->name,
                        'score' => $item->score,
                    ];
                });

            // Fetch Coaches for the player's group
            $coaches = Coach::where('group_id', $player->group_id)->get();
            $headCoach = $coaches->first(function ($c) {
                return strtolower(trim($c->position)) === 'head coach';
            });
            $assistantCoach = $coaches->first(function ($c) {
                return strtolower(trim($c->position)) === 'assistant coach';
            });

            // Fetch dynamic position recommendations
            $recommendedPositionId = null;
            $recommendedPositionName = null;

            if ($evaluation->pairwise_set_id) {
                try {
                    $recommendations = $this->playerScoreMappingService->getPositionRecommendations($evaluationId);
                    $playerRecs = [];
                    foreach ($recommendations as $rec) {
                        if ($rec['player_id'] === $playerId) {
                            $playerRecs = $rec['positions'];
                            break;
                        }
                    }
                    if (! empty($playerRecs)) {
                        $recommendedPositionId = $playerRecs[0]['position_id'] ?? null;
                        $recommendedPositionName = $playerRecs[0]['position_name'] ?? null;
                    }
                } catch (Exception $e) {
                    // Fail silently, keep null
                }
            }

            return [
                'report_id' => null,
                'evaluation_id' => $evaluationId,
                'evaluation_title' => $evaluation->title,
                'evaluation_date' => $evaluation->date,
                'player_id' => $playerId,
                'player_name' => $player->name,
                'group_name' => $player->group ? $player->group->age_group : null,
                'head_coach' => $headCoach ? $headCoach->name : null,
                'assistant_coach' => $assistantCoach ? $assistantCoach->name : null,
                'recommended_position_id' => $recommendedPositionId,
                'recommended_position_name' => $recommendedPositionName,
                'final_position_id' => null,
                'final_position_name' => null,
                'notes' => null,
                'scores' => $scores,
            ];
        }

        // 2. Fetch evaluation scores for finalized report
        $scores = EvaluationScore::with('subCriteria.criteria')
            ->where('evaluation_id', $evaluationId)
            ->where('player_id', $playerId)
            ->get()
            ->map(function ($item) {
                return [
                    'sub_criteria_id' => $item->sub_criteria_id,
                    'sub_criteria_name' => $item->subCriteria->name,
                    'criteria_id' => $item->subCriteria->criteria_id,
                    'criteria_name' => $item->subCriteria->criteria->name,
                    'score' => $item->score,
                ];
            });

        // 3. Fetch Coaches for the player's group
        $player = $report->player;
        $coaches = Coach::where('group_id', $player->group_id)->get();
        $headCoach = $coaches->first(function ($c) {
            return strtolower(trim($c->position)) === 'head coach';
        });
        $assistantCoach = $coaches->first(function ($c) {
            return strtolower(trim($c->position)) === 'assistant coach';
        });

        return [
            'report_id' => $report->id,
            'evaluation_id' => $report->evaluation_id,
            'evaluation_title' => $report->evaluation->title,
            'evaluation_date' => $report->evaluation->date,
            'player_id' => $report->player_id,
            'player_name' => $player->name,
            'group_name' => $player->group ? $player->group->age_group : null,
            'head_coach' => $headCoach ? $headCoach->name : null,
            'assistant_coach' => $assistantCoach ? $assistantCoach->name : null,
            'recommended_position_id' => $report->recommended_position_id,
            'recommended_position_name' => $report->recommendedPosition ? $report->recommendedPosition->name : null,
            'final_position_id' => $report->final_position_id,
            'final_position_name' => $report->finalPosition ? $report->finalPosition->name : null,
            'notes' => $report->notes,
            'scores' => $scores,
        ];
    }

    public function getReportsListForPlayer(int $playerId)
    {
        $reports = $this->repository->getReportsByPlayer($playerId);

        return $reports->map(function ($report) {
            return [
                'evaluation_id' => $report->evaluation_id,
                'evaluation_title' => $report->evaluation->title,
                'evaluation_date' => $report->evaluation->date,
                'final_position_id' => $report->final_position_id,
                'final_position_name' => $report->finalPosition ? $report->finalPosition->name : null,
                'finalized_at' => $report->created_at->toDateTimeString(),
            ];
        });
    }

    public function getPlayersForFinalization(int $evaluationId): array
    {
        $evaluation = Evaluation::find($evaluationId);
        if (! $evaluation) {
            throw new Exception(ErrorMessages::EVALUATION_NOT_FOUND);
        }

        $coach = $evaluation->coach;
        if (! $coach) {
            throw new Exception(ErrorMessages::COACH_NOT_FOUND);
        }

        $players = Player::where('group_id', $coach->group_id)->get();

        $recommendations = [];
        if ($evaluation->pairwise_set_id) {
            try {
                $recommendations = $this->playerScoreMappingService->getPositionRecommendations($evaluationId);
            } catch (Exception $e) {
                // If calculations fail (e.g. empty/incomplete pairwise_set), keep empty
                $recommendations = [];
            }
        }

        $recommendationsByPlayer = [];
        foreach ($recommendations as $rec) {
            $recommendationsByPlayer[$rec['player_id']] = $rec['positions'];
        }

        $data = [];
        foreach ($players as $player) {
            $statusFinalisasi = $this->repository->hasReport($evaluationId, $player->id);
            $playerRecs = $recommendationsByPlayer[$player->id] ?? [];
            $data[] = [
                'player_id' => $player->id,
                'player_name' => $player->name,
                'status_finalisasi' => $statusFinalisasi,
                'recommendations' => $playerRecs,
            ];
        }

        return $data;
    }

    public function saveRecommendationDrafts(int $evaluationId, array $recommendations)
    {
        foreach ($recommendations as $rec) {
            $playerId = (int) $rec['player_id'];
            $positions = $rec['positions'] ?? [];
            if (empty($positions)) {
                continue;
            }

            // Rekomendasi teratas (posisi pertama hasil AHP)
            $topPositionId = (int) $positions[0]['position_id'];

            $this->repository->saveDraft([
                'evaluation_id' => $evaluationId,
                'player_id' => $playerId,
                'recommended_position_id' => $topPositionId,
                'final_position_id' => $topPositionId, // Default ke posisi teratas
                'notes' => null,
                'is_finalized' => false,
            ]);
        }
    }
}
