<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Services\CriteriaSetService;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CriteriaSetController extends Controller
{
    public function __construct(
        protected CriteriaSetService $service
    ) {}

    public function index(): JsonResponse
    {
        try {
            $data = $this->service->getAllSets();

            return response()->json(
                (new BaseResponse(
                    true,
                    'Criteria sets retrieved successfully',
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

    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'group_id' => 'required|integer',
            ]);

            $data = $this->service->createSet($validated);

            return response()->json(
                (new BaseResponse(
                    true,
                    'Criteria set created successfully',
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

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'group_id' => 'sometimes|required|integer',
            ]);

            $data = $this->service->updateSet($id, $validated);

            return response()->json(
                (new BaseResponse(
                    true,
                    'Criteria set updated successfully',
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

    public function destroy(int $id): JsonResponse
    {
        try {
            $this->service->deleteSet($id);

            return response()->json(
                (new BaseResponse(
                    true,
                    'Criteria set deleted successfully'
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
