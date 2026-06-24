<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Services\Interfaces\IGroupService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class GroupController extends Controller
{
    protected IGroupService $service;

    public function __construct(IGroupService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => $this->service->getAll(),
        ]);
    }

    public function createGroup(Request $request)
    {
        $data = $this->service->createGroup($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Group created',
            'data' => $data,
        ]);
    }

    public function updateGroup(Request $request, $id)
    {
        $data = $this->service->updateGroup($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Group updated',
            'data' => $data,
        ]);
    }

    public function deleteGroup($id)
    {
        $this->service->deleteGroup($id);

        return response()->json([
            'success' => true,
            'message' => 'Group deleted',
        ]);
    }
}
