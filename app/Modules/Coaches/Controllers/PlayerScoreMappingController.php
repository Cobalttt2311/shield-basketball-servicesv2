<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Models\Evaluation;
use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Services\Interfaces\IPlayerScoreMappingService;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;

class PlayerScoreMappingController extends Controller
{
    protected $service;

    public function __construct(
        IPlayerScoreMappingService $service
    ) {
        $this->service = $service;
    }

    public function calculate(
        int $evaluationId,
        int $subCriteriaId
    ) {
        try {
            $data = $this->service
                ->calculateAlternativeWeights(
                    $evaluationId,
                    $subCriteriaId
                );

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::WEIGHTS_CALCULATED,
                    $data
                ))->toArray()
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                400
            );
        }
    }

    public function calculateAlternativeScores(
        int $evaluationId,
        int $positionId
    ) {
        try {
            $position = Position::find($positionId);
            $scores = $this->service
                ->calculateAlternativeScores(
                    $evaluationId,
                    $positionId
                );

            $data = [
                'position_id' => $positionId,
                'position_name' => $position ? $position->name : null,
                'players' => $scores,
            ];

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::ALTERNATIVE_SCORES_CALCULATED,
                    $data
                ))->toArray()
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                400
            );
        }
    }

    public function getPositionRecommendations(
        int $evaluationId
    ) {
        try {
            $data = $this->service
                ->getPositionRecommendations(
                    $evaluationId
                );

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::RECOMMENDATIONS_GET,
                    $data
                ))->toArray()
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                400
            );
        }
    }

    public function calculateAlternativeScoresByPosition(
        int $positionId
    ) {
        try {
            $latestEvaluation = Evaluation::latest('id')->first();
            if (! $latestEvaluation) {
                return response()->json(
                    (new BaseResponse(
                        false,
                        'No evaluations found',
                        null,
                        'EVALUATIONS_EMPTY'
                    ))->toArray(),
                    404
                );
            }

            $position = Position::find($positionId);
            $scores = $this->service
                ->calculateAlternativeScores(
                    $latestEvaluation->id,
                    $positionId
                );

            $data = [
                'position_id' => $positionId,
                'position_name' => $position ? $position->name : null,
                'players' => $scores,
            ];

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::ALTERNATIVE_SCORES_CALCULATED,
                    $data
                ))->toArray()
            );
        } catch (\InvalidArgumentException $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                400
            );
        }
    }
}
