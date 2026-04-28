<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Modules\Admin\Services\Interfaces\IGroupService;

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
            'data' => $this->service->getAll()
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->service->create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Group created',
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $this->service->update($id, $request->all());

        return response()->json([
            'success' => true,
            'message' => 'Group updated',
            'data' => $data
        ]);
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => true,
            'message' => 'Group deleted'
        ]);
    }
}