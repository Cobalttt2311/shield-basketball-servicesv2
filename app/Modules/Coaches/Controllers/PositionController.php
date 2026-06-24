<?php

namespace App\Modules\Coaches\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Coaches\Services\PositionService;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    protected $service;

    public function __construct(
        PositionService $service
    ) {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json(
            $this->service->getAll()
        );
    }

    public function getByGroup(
        int $groupId
    ) {
        return response()->json(
            $this->service->getByGroupId(
                $groupId
            )
        );
    }

    public function show(
        int $id
    ) {
        return response()->json(
            $this->service->getById($id)
        );
    }

    public function store(
        Request $request
    ) {
        $validated =
            $request->validate([
                'group_id' => 'required|exists:groups,id',

                'name' => 'required|string|max:255',
            ]);

        $position =
            $this->service->create(
                $validated
            );

        return response()->json([
            'message' => 'Position created successfully',
            'data' => $position,
        ], 201);
    }

    public function update(
        Request $request,
        int $id
    ) {
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

        return response()->json([
            'message' => 'Position updated successfully',
            'data' => $position,
        ]);
    }

    public function destroy(
        int $id
    ) {
        $this->service->delete($id);

        return response()->json([
            'message' => 'Position deleted successfully',
        ]);
    }
}
