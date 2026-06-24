<?php

namespace App\Http\Middleware;

use App\Modules\Admin\Models\Coach;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use App\Utils\Responses\BaseResponse;
use Closure;
use Illuminate\Http\Request;

class HeadCoachMiddleware
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

        // Check if user role is coach
        if ($user->role !== 'coach') {
            return response()->json(
                (new BaseResponse(
                    false,
                    ErrorMessages::FORBIDDEN_HEAD_COACH,
                    null,
                    'Forbidden'
                ))->toArray(),
                403
            );
        }

        // Check if the coach has position 'Head Coach'
        $coach = Coach::where('user_id', $user->id)->first();
        if (! $coach || trim($coach->position) !== 'Head Coach') {
            return response()->json(
                (new BaseResponse(
                    false,
                    ErrorMessages::FORBIDDEN_HEAD_COACH,
                    null,
                    'Forbidden'
                ))->toArray(),
                403
            );
        }

        return $next($request);
    }
}
