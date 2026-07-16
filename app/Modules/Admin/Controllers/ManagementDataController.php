<?php

namespace App\Modules\Admin\Controllers;

use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Player;
use App\Modules\Admin\Services\Interfaces\IManagementDataService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

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
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'group_id' => 'required|integer|exists:groups,id',
                'position' => 'required|string|max:255',
                'phone_number' => 'required|string|max:255',
                'birth_date' => 'nullable|date',
                'license' => 'nullable|string|max:255',
                'is_master' => 'nullable|boolean',
            ]);

            $result = $this->service->createCoach($validated);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::COACH_CREATED, $result))->toArray(),
                201
            );
        } catch (ValidationException $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->errors()))->toArray(),
                422
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
            $coach = Coach::findOrFail($id);
            $userId = $coach->user_id;

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$userId.',id|unique:coaches,email,'.$id.',id',
                'group_id' => 'required|integer|exists:groups,id',
                'position' => 'required|string|max:255',
                'phone_number' => 'required|string|max:255',
                'birth_date' => 'nullable|date',
                'license' => 'nullable|string|max:255',
                'is_master' => 'nullable|boolean',
            ]);

            $result = $this->service->updateCoach($id, $validated);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::COACH_UPDATED, $result))->toArray()
            );
        } catch (ValidationException $e) {
            return response()->json(
                (new BaseResponse(false, $e->validator->errors()->first(), null, $e->errors()))->toArray(),
                422
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
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'group_id' => 'required|integer|exists:groups,id',
                'phone_number' => 'required|string|max:255',
                'birth_date' => 'nullable|date',
                'height' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'parent_name' => 'nullable|string|max:255',
                'parent_phone' => 'nullable|string|max:255',
            ]);

            $result = $this->service->createPlayer($validated);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::PLAYER_CREATED, $result))->toArray(),
                201
            );
        } catch (ValidationException $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->errors()))->toArray(),
                422
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
            $player = Player::findOrFail($id);
            $userId = $player->user_id;

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,'.$userId.',id|unique:players,email,'.$id.',id',
                'group_id' => 'required|integer|exists:groups,id',
                'phone_number' => 'required|string|max:255',
                'birth_date' => 'nullable|date',
                'height' => 'nullable|numeric',
                'weight' => 'nullable|numeric',
                'parent_name' => 'nullable|string|max:255',
                'parent_phone' => 'nullable|string|max:255',
            ]);

            $result = $this->service->updatePlayer($id, $validated);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::PLAYER_UPDATED, $result))->toArray()
            );
        } catch (ValidationException $e) {
            return response()->json(
                (new BaseResponse(false, $e->validator->errors()->first(), null, $e->errors()))->toArray(),
                422
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
