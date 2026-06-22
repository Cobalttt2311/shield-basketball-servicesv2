<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Services\PairwiseCriteriaService;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\Request;

class PairwiseCriteriaController extends Controller
{
    protected $service;

    public function __construct(
        PairwiseCriteriaService $service
    ) {
        $this->service = $service;
    }

    public function generate(
        int $groupId,
        int $positionId
    ) {
        try {
            $this->service->generatePairwise($groupId, $positionId);

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
                '*.criteria_first_id' => 'required|exists:criteria,id',
                '*.criteria_second_id' => 'required|exists:criteria,id',
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
        int $groupId,
        int $positionId
    ) {
        try {
            $data = $this->service->generateMatrix($groupId, $positionId);

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
        int $groupId,
        int $positionId
    ) {
        try {
            $data = $this->service->calculateWeights($groupId, $positionId);

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
        int $groupId,
        int $positionId
    ) {
        try {
            $this->service->saveWeights($groupId, $positionId);

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
        int $groupId,
        int $positionId
    ) {
        try {
            $data = $this->service->calculateConsistencyRatio($groupId, $positionId);

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
        int $groupId,
        int $positionId
    ) {
        try {
            $data = $this->service->getPairwise($groupId, $positionId);

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
