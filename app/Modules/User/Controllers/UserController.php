<?php

namespace App\Modules\User\Controllers;

use Illuminate\Routing\Controller;
use App\Utils\Requests\LoginRequest;
use App\Modules\User\Services\Interfaces\IUserService;
use Illuminate\Http\Request;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use App\Utils\Responses\BaseResponse;

class UserController extends Controller
{
    protected IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function login(LoginRequest $request)
    {
        $result = $this->userService->login(
            $request->login,
            $request->password
        );

        if (!$result) {
            $response = new BaseResponse(
                false,
                ErrorMessages::AUTH_INVALID_CREDENTIALS,
                null,
                'LOGIN_FAILED'
            );

            return response()->json($response->toArray(), 401);
        }

        $response = new BaseResponse(
            true,
            SuccessMessages::LOGIN_SUCCESS,
            [
                'user'  => $result['user'],
                'token' => $result['token'],
            ]
        );

        return response()->json($response->toArray(), 200);
    }

    public function logout(Request $request)
    {
        $this->userService->logout($request->user());

        $response = new BaseResponse(
            true,
            SuccessMessages::LOGOUT_SUCCESS
        );

        return response()->json($response->toArray(), 200);
    }
}