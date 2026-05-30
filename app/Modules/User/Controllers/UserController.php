<?php

namespace App\Modules\User\Controllers;

use Illuminate\Routing\Controller;
use App\Utils\Requests\LoginRequest;
use App\Utils\Requests\ForgotPasswordRequest;
use App\Utils\Requests\ResetPasswordRequest;  
use App\Modules\User\Services\Interfaces\IUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = $this->userService->sendResetLinkEmail(
            $request->email
        );

        if ($status === Password::RESET_LINK_SENT) {
            return view('auth.forgot-success');
        }

        return view('auth.forgot-password', [
            'error' => 'Email tidak ditemukan'
        ]);
    }
    
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = $this->userService->resetPassword(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            )
        );

        if ($status === Password::PASSWORD_RESET) {
            return view('auth.reset-success');
        }

        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
            'error' => 'Token reset tidak valid',
        ]);
    }

    public function showResetPasswordForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }
}