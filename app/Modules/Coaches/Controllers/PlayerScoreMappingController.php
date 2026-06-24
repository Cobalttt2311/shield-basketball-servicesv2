<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Models\Evaluation;
use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Services\Interfaces\IPairwiseSetService;
use App\Modules\Coaches\Services\Interfaces\IPlayerScoreMappingService;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\Request;

class PlayerScoreMappingController extends Controller
{
    protected $service;

    protected IPairwiseSetService $pairwiseSetService;

    public function __construct(
        IPlayerScoreMappingService $service,
        IPairwiseSetService $pairwiseSetService
    ) {
        $this->service = $service;
        $this->pairwiseSetService = $pairwiseSetService;
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

    public function processRecommendation(Request $request, int $evaluationId)
    {
        try {
            $request->validate([
                'pairwise_set_id' => 'required|integer',
            ]);

            $pairwiseSetId = (int) $request->pairwise_set_id;

            // 1. Ambil & Validasi kompatibilitas
            $compatibleSets = $this->pairwiseSetService->getCompatibleSets($evaluationId);
            $compatibleIds = array_column($compatibleSets, 'id');

            if (! in_array($pairwiseSetId, $compatibleIds)) {
                return response()->json(
                    (new BaseResponse(
                        false,
                        'Pairwise set is not compatible with the current evaluation criteria.',
                        null,
                        'INCOMPATIBLE_PAIRWISE_SET'
                    ))->toArray(),
                    400
                );
            }

            // 2. Simpan pairwise_set_id ke evaluations
            $evaluation = Evaluation::find($evaluationId);
            $evaluation->pairwise_set_id = $pairwiseSetId;
            $evaluation->save();

            // 3. Proses kalkulasi AHP & dapatkan rekomendasi
            $data = $this->service->getPositionRecommendations($evaluationId);

            return response()->json(
                (new BaseResponse(
                    true,
                    'Recommendation processed and calculated successfully',
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
        } catch (\Exception $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    'SERVER_ERROR'
                ))->toArray(),
                500
            );
        }
    }
}
