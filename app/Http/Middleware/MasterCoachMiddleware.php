<?php

namespace App\Http\Middleware;

use App\Utils\Messages\ErrorMessages\ErrorMessages;
use App\Utils\Responses\BaseResponse;
use Closure;
use Illuminate\Http\Request;

class MasterCoachMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(
                (new BaseResponse(
                    false,
                    ErrorMessages::AUTH_UNAUTHORIZED,
                    null,
                    ErrorMessages::AUTH_UNAUTHORIZED
                ))->toArray(),
                401
            );
        }

        // Check if user has master_coach role in the roles array
        if (! in_array('master_coach', $user->roles)) {
            return response()->json(
                (new BaseResponse(
                    false,
                    ErrorMessages::FORBIDDEN_HEAD_COACH, // Reusing Head Coach Forbidden Msg or customized
                    null,
                    'Forbidden'
                ))->toArray(),
                403
            );
        }

        return $next($request);
    }
}
