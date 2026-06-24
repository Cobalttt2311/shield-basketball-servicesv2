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
        Request $request,
        int $positionId,
        int $criteriaId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $this->service->generatePairwise($positionId, $criteriaId, $pairwiseSetId);

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
                'items.*.criteria_id' => 'required|exists:criteria,id',
                'items.*.sub_criteria_first_id' => 'required|exists:sub_criteria,id',
                'items.*.sub_criteria_second_id' => 'required|exists:sub_criteria,id',
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
        int $positionId,
        int $criteriaId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $data = $this->service->generateMatrix($positionId, $criteriaId, $pairwiseSetId);

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
        int $positionId,
        int $criteriaId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $data = $this->service->calculateWeights($positionId, $criteriaId, $pairwiseSetId);

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
        int $positionId,
        int $criteriaId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $this->service->saveWeights($positionId, $criteriaId, $pairwiseSetId);

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
        int $positionId,
        int $criteriaId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $data = $this->service->calculateConsistencyRatio($positionId, $criteriaId, $pairwiseSetId);

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
        int $positionId,
        int $criteriaId
    ) {
        try {
            $pairwiseSetId = $request->query('pairwise_set_id');
            $data = $this->service->getPairwise($positionId, $criteriaId, $pairwiseSetId);

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
