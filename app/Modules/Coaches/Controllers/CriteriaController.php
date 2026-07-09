<?php

namespace App\Modules\Coaches\Controllers;

use App\Modules\Coaches\Services\Interfaces\ICriteriaService;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Throwable;

class CriteriaController extends Controller
{
    protected ICriteriaService $service;

    public function __construct(ICriteriaService $service)
    {
        $this->service = $service;
    }

    public function getMyCriteria()
    {
        try {
            $data = $this->service->getMyCriteria();

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::CRITERIA_GET,
                    $data
                ))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function getCriteriaById($id)
    {
        try {
            $data = $this->service->getCriteriaById($id);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::CRITERIA_GET,
                    $data
                ))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function getCriteriaByGroupId($groupId)
    {
        try {
            $data = $this->service->getCriteriaByGroupId($groupId);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::CRITERIA_GET,
                    $data
                ))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function getCriteriaBySetId($setId)
    {
        try {
            $data = $this->service->getCriteriaBySetId((int) $setId);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::CRITERIA_GET,
                    $data
                ))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function createCriteria(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'criteria_set_id' => 'required|integer',
            ]);

            $result = $this->service->createCriteria([
                'name' => $request->name,
                'criteria_set_id' => (int) $request->criteria_set_id,
            ]);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::CRITERIA_CREATED,
                    $result
                ))->toArray(),
                201
            );
        } catch (Throwable $e) {
            $status = 500;
            if ($e->getMessage() === 'Criteria already exists') {
                $status = 400;
            }
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                $status
            );
        }
    }

    public function updateCriteria(Request $request, $id)
    {
        try {
            $result = $this->service->updateCriteria($id, [
                'name' => $request->name,
            ]);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::CRITERIA_UPDATED,
                    $result
                ))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function deleteCriteria($id)
    {
        try {
            $this->service->deleteCriteria($id);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::CRITERIA_DELETED
                ))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function createSubCriteria(Request $request)
    {
        try {
            $result = $this->service->createSubCriteria([
                'criteria_id' => $request->criteria_id,
                'name' => $request->name,
            ]);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::SUBCRITERIA_CREATED,
                    $result
                ))->toArray(),
                201
            );
        } catch (Throwable $e) {
            $status = 500;
            if ($e->getMessage() === 'Sub criteria already exists') {
                $status = 400;
            }
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                $status
            );
        }
    }

    public function getAllSubCriteria()
    {
        try {
            $data = $this->service->getAllSubCriteria();

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::CRITERIA_GET,
                    $data
                ))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function getSubCriteriaByCriteria($criteriaId)
    {
        try {
            $data = $this->service->getSubCriteriaByCriteria($criteriaId);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::CRITERIA_GET,
                    $data
                ))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function updateSubCriteria(Request $request, $id)
    {
        try {
            $result = $this->service->updateSubCriteria($id, [
                'name' => $request->name,
            ]);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::SUBCRITERIA_UPDATED,
                    $result
                ))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function deleteSubCriteria($id)
    {
        try {
            $this->service->deleteSubCriteria($id);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::SUBCRITERIA_DELETED
                ))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function indexSets(): JsonResponse
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
        } catch (Throwable $e) {
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

    public function storeSets(Request $request): JsonResponse
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
        } catch (Throwable $e) {
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

    public function updateSets(Request $request, $id): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'group_id' => 'sometimes|required|integer',
            ]);

            $data = $this->service->updateSet((int) $id, $validated);

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
        } catch (Throwable $e) {
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

    public function destroySets($id): JsonResponse
    {
        try {
            $this->service->deleteSet((int) $id);

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
        } catch (Throwable $e) {
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
