<?php

namespace App\Modules\Coaches\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Modules\Coaches\Services\Interfaces\ICriteriaService;
use App\Utils\Responses\BaseResponse;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Messages\ErrorMessages\ErrorMessages;

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
                (new BaseResponse(true, SuccessMessages::CRITERIA_GET, $data))->toArray()
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function createCriteria(Request $request)
    {
        try {
            $data = $request->only(['name']);

            $result = $this->service->createCriteria($data);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::CRITERIA_CREATED, $result))->toArray(),
                201
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function createSubCriteria(Request $request)
    {
        try {
            $data = $request->only([
                'criteria_id',
                'name'
            ]);

            $result = $this->service->createSubCriteria($data);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::SUBCRITERIA_CREATED, $result))->toArray(),
                201
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }
}