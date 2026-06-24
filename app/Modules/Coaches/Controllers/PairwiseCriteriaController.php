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
        Request $request,
        int $groupId,
        int $positionId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $this->service->generatePairwise($groupId, $positionId, $pairwiseSetId);

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
                'pairwise_set_id' => 'integer|nullable',
                'items' => 'required|array',
                'items.*.position_id' => 'required|exists:positions,id',
                'items.*.criteria_first_id' => 'required|exists:criteria,id',
                'items.*.criteria_second_id' => 'required|exists:criteria,id',
                'items.*.value' => 'required|numeric|min:0.111|max:9',
            ]);

            $pairwiseSetId = $validated['pairwise_set_id'] ?? null;
            $this->service->saveValue($validated['items'], $pairwiseSetId);

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
        Request $request,
        int $groupId,
        int $positionId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $data = $this->service->generateMatrix($groupId, $positionId, $pairwiseSetId);

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
        Request $request,
        int $groupId,
        int $positionId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $data = $this->service->calculateWeights($groupId, $positionId, $pairwiseSetId);

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
        Request $request,
        int $groupId,
        int $positionId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $this->service->saveWeights($groupId, $positionId, $pairwiseSetId);

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
        Request $request,
        int $groupId,
        int $positionId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $data = $this->service->calculateConsistencyRatio($groupId, $positionId, $pairwiseSetId);

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
        Request $request,
        int $groupId,
        int $positionId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $data = $this->service->getPairwise($groupId, $positionId, $pairwiseSetId);

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
