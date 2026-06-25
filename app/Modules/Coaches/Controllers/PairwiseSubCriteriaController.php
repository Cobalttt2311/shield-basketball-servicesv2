<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Services\PairwiseSubCriteriaService;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PairwiseSubCriteriaController extends Controller
{
    public function __construct(
        protected PairwiseSubCriteriaService $service
    ) {}

    public function generate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'pairwise_set_id' => 'required|integer',
                'criteria_id' => 'required|integer',
            ]);

            $this->service->generatePairwiseForSet(
                (int) $request->pairwise_set_id,
                (int) $request->criteria_id
            );

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

    public function save(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'pairwise_set_id' => 'required|integer',
                'comparisons' => 'required|array',
                'comparisons.*.id' => 'required|integer',
                'comparisons.*.value' => 'required|numeric|min:0.111|max:9',
            ]);

            $this->service->saveValueForSet((int) $validated['pairwise_set_id'], $validated['comparisons']);

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

    public function getPairwise(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'pairwise_set_id' => 'required|integer',
                'criteria_id' => 'required|integer',
            ]);

            $data = $this->service->getPairwiseForSet(
                (int) $request->pairwise_set_id,
                (int) $request->criteria_id
            );

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

    public function calculateWeights(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'pairwise_set_id' => 'required|integer',
                'criteria_id' => 'required|integer',
            ]);

            $res = $this->service->calculateAndSaveWeightsForSet(
                (int) $request->pairwise_set_id,
                (int) $request->criteria_id
            );

            if (! $res['success']) {
                return response()->json(
                    (new BaseResponse(
                        false,
                        $res['message'] ?? 'Beberapa perbandingan sub-kriteria belum diisi.',
                        null,
                        $res['errors']
                    ))->toArray(),
                    422
                );
            }

            return response()->json(
                (new BaseResponse(
                    true,
                    'Bobot sub-kriteria berhasil dihitung.',
                    $res['results']
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
