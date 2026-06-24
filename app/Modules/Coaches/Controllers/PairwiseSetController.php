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
            $request->validate([
                'evaluation_id' => 'required|integer',
            ]);

            $data = $this->service->getCompatibleSets((int) $request->evaluation_id);

            return response()->json(
                (new BaseResponse(
                    true,
                    'Compatible pairwise sets retrieved successfully',
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
            ]);

            $data = $this->service->createSet($request->only(['name', 'group_id']));

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
}
