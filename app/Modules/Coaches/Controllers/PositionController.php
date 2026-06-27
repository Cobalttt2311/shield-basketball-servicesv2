<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Services\PositionService;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected $service;

    public function __construct(
        PositionService $service
        )
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json(
            (new BaseResponse(
            true,
            'Positions retrieved successfully',
            $this->service->getAll()
            ))->toArray()
        );
    }

    public function getByGroup(
        int $groupId
        )
    {
        return response()->json(
            (new BaseResponse(
            true,
            'Positions retrieved successfully',
            $this->service->getByGroupId(
            $groupId
        )
            ))->toArray()
        );
    }

    public function show(
        int $id
        )
    {
        return response()->json(
            (new BaseResponse(
            true,
            'Position retrieved successfully',
            $this->service->getById($id)
            ))->toArray()
        );
    }

    public function store(
        Request $request
        )
    {
        $validated =
            $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
        ]);

        $position =
            $this->service->create(
            $validated
        );

        return response()->json(
            (new BaseResponse(
            true,
            'Position created successfully',
            $position
            ))->toArray(),
            201
        );
    }

    public function update(
        Request $request,
        int $id
        )
    {
        $validated =
            $request->validate([
            'group_id' => 'required|exists:groups,id',
            'name' => 'required|string|max:255',
        ]);

        $position =
            $this->service->update(
            $id,
            $validated
        );

        return response()->json(
            (new BaseResponse(
            true,
            'Position updated successfully',
            $position
            ))->toArray()
        );
    }

    public function destroy(
        int $id
        )
    {
        $this->service->delete($id);

        return response()->json(
            (new BaseResponse(
            true,
            'Position deleted successfully'
            ))->toArray()
        );
    }
}