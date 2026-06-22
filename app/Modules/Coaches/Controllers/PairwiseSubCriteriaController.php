<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Services\PairwiseSubCriteriaService;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\Request;

class PairwiseSubCriteriaController extends Controller
{
    protected $service;

    public function __construct(
        PairwiseSubCriteriaService $service
    ) {
        $this->service = $service;
    }

    public function generate(
        int $positionId,
        int $criteriaId
    ) {
        try {
            $this->service->generatePairwise($positionId, $criteriaId);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::PAIRWISE_GENERATED
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

    public function save(
        Request $request
    ) {
        try {
            $validated = $request->validate([
                '*.position_id' => 'required|exists:positions,id',
                '*.criteria_id' => 'required|exists:criteria,id',
                '*.sub_criteria_first_id' => 'required|exists:sub_criteria,id',
                '*.sub_criteria_second_id' => 'required|exists:sub_criteria,id',
                '*.value' => 'required|numeric|min:0.111|max:9',
            ]);

            $this->service->saveValue($validated);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::PAIRWISE_SAVED
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

    public function matrix(
        int $positionId,
        int $criteriaId
    ) {
        try {
            $data = $this->service->generateMatrix($positionId, $criteriaId);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::MATRIX_GENERATED,
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

    public function weights(
        int $positionId,
        int $criteriaId
    ) {
        try {
            $data = $this->service->calculateWeights($positionId, $criteriaId);

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

    public function saveWeights(
        int $positionId,
        int $criteriaId
    ) {
        try {
            $this->service->saveWeights($positionId, $criteriaId);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::WEIGHTS_SAVED
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

    public function consistency(
        int $positionId,
        int $criteriaId
    ) {
        try {
            $data = $this->service->calculateConsistencyRatio($positionId, $criteriaId);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::CONSISTENCY_CALCULATED,
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

    public function getPairwise(
        int $positionId,
        int $criteriaId
    ) {
        try {
            $data = $this->service->getPairwise($positionId, $criteriaId);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::PAIRWISE_RETRIEVED,
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
