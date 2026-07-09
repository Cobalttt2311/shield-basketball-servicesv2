<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Services\Interfaces\IPairwiseSetService;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\Request;

class PairwiseSetController extends Controller
{
    protected IPairwiseSetService $service;

    public function __construct(IPairwiseSetService $service)
    {
        $this->service = $service;
    }

    public function getCompatibleSets(Request $request)
    {
        try {
            $evaluationId = $request->has('evaluation_id') ? (int) $request->evaluation_id : null;

            $data = $this->service->getCompatibleSets($evaluationId);

            return response()->json(
                (new BaseResponse(
                    true,
                    $evaluationId === null ? 'All pairwise sets retrieved successfully' : 'Compatible pairwise sets retrieved successfully',
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

    public function createSet(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'group_id' => 'required|integer',
                'criteria_set_id' => 'required|integer',
            ]);

            $data = $this->service->createSet($request->only(['name', 'group_id', 'criteria_set_id']));

            return response()->json(
                (new BaseResponse(
                    true,
                    'Pairwise set created successfully',
                    $data
                ))->toArray(),
                201
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

    public function updateSet(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'group_id' => 'nullable|integer',
                'criteria_set_id' => 'nullable|integer',
            ]);

            $data = $this->service->updateSet((int) $id, $request->only(['name', 'group_id', 'criteria_set_id']));

            return response()->json(
                (new BaseResponse(
                    true,
                    'Pairwise set updated successfully',
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

    public function getWeights($id)
    {
        try {
            $data = $this->service->getWeights((int) $id);

            return response()->json(
                (new BaseResponse(
                    true,
                    'Weights retrieved successfully',
                    $data
                ))->toArray()
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
