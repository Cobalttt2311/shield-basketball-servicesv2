<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Services\Interfaces\IManagementDataService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ManagementDataController extends Controller
{
    protected IManagementDataService $service;

    public function __construct(IManagementDataService $service)
    {
        $this->service = $service;
    }

    public function getCoaches(Request $request)
    {
        try {
            $groupId = $request->query('group_id');

            $data = $this->service->getAllCoaches($groupId);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::COACH_GET, $data))->toArray()
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function getCoachDetail($id)
    {
        try {
            $data = $this->service->getCoachDetail($id);

            if (! $data) {
                return response()->json(
                    (new BaseResponse(false, ErrorMessages::COACH_NOT_FOUND))->toArray(),
                    404
                );
            }

            return response()->json(
                (new BaseResponse(true, SuccessMessages::COACH_GET, $data))->toArray()
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function storeCoach(Request $request)
    {
        try {
            $data = $request->only([
                'name',
                'email',
                'birth_date',
                'group_id',
                'position',
                'license',
                'phone_number',
                'is_master',
            ]);

            $result = $this->service->createCoach($data);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::COACH_CREATED, $result))->toArray(),
                201
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function updateCoach(Request $request, $id)
    {
        try {
            $data = $request->only([
                'name',
                'birth_date',
                'group_id',
                'position',
                'license',
                'phone_number',
                'email',
                'is_master',
            ]);

            $result = $this->service->updateCoach($id, $data);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::COACH_UPDATED, $result))->toArray()
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function deleteCoach($id)
    {
        try {
            $success = $this->service->deleteCoach($id);
            if (! $success) {
                return response()->json(
                    (new BaseResponse(false, ErrorMessages::COACH_NOT_FOUND))->toArray(),
                    404
                );
            }

            return response()->json(
                (new BaseResponse(true, SuccessMessages::COACH_DELETED))->toArray()
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function getPlayers(Request $request)
    {
        try {
            $groupId = $request->query('group_id');

            $data = $this->service->getAllPlayers($groupId);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::PLAYER_GET, $data))->toArray()
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function getPlayerDetail($id)
    {
        try {
            $data = $this->service->getPlayerDetail($id);

            if (! $data) {
                return response()->json(
                    (new BaseResponse(false, ErrorMessages::PLAYER_NOT_FOUND))->toArray(),
                    404
                );
            }

            return response()->json(
                (new BaseResponse(true, SuccessMessages::PLAYER_GET, $data))->toArray()
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function storePlayer(Request $request)
    {
        try {
            $data = $request->only([
                'name',
                'email',
                'birth_date',
                'group_id',
                'phone_number',
                'height',
                'weight',
                'parent_name',
                'parent_phone',
            ]);

            $result = $this->service->createPlayer($data);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::PLAYER_CREATED, $result))->toArray(),
                201
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function updatePlayer(Request $request, $id)
    {
        try {
            $data = $request->only([
                'name',
                'birth_date',
                'group_id',
                'phone_number',
                'email',
                'height',
                'weight',
                'parent_name',
                'parent_phone',
            ]);

            $result = $this->service->updatePlayer($id, $data);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::PLAYER_UPDATED, $result))->toArray()
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function deletePlayer($id)
    {
        try {
            $success = $this->service->deletePlayer($id);
            if (! $success) {
                return response()->json(
                    (new BaseResponse(false, ErrorMessages::PLAYER_NOT_FOUND))->toArray(),
                    404
                );
            }

            return response()->json(
                (new BaseResponse(true, SuccessMessages::PLAYER_DELETED))->toArray()
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }

    public function getPlayerByGroupCoach(Request $request)
    {
        try {
            $user = $request->user();
            if (! $user) {
                return response()->json(
                    (new BaseResponse(false, 'Unauthorized'))->toArray(),
                    401
                );
            }

            $coach = $user->coach;
            if (! $coach) {
                return response()->json(
                    (new BaseResponse(false, ErrorMessages::COACH_NOT_FOUND))->toArray(),
                    404
                );
            }

            $groupId = $coach->group_id;
            if (! $groupId) {
                return response()->json(
                    (new BaseResponse(false, 'Coach does not have an assigned age group'))->toArray(),
                    400
                );
            }

            $data = $this->service->getAllPlayers($groupId);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::PLAYER_GET, $data))->toArray()
            );
        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }
}
