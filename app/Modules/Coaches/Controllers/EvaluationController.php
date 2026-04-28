<?php

namespace App\Modules\Coaches\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Modules\Coaches\Services\Interfaces\IEvaluationService;
use App\Utils\Responses\BaseResponse;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Messages\ErrorMessages\ErrorMessages;

class EvaluationController extends Controller
{
    protected IEvaluationService $service;

    public function __construct(IEvaluationService $service)
    {
        $this->service = $service;
    }

    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            if ($user->role !== 'coach' || !$user->coach) {
                return response()->json(
                    (new BaseResponse(false, ErrorMessages::COACH_NOT_FOUND))->toArray(),
                    404
                );
            }

            $coach = $user->coach;

            // dd($request->all());

            $data = $request->all();

            if (empty($data['scores']) || !is_array($data['scores'])) {
                return response()->json(
                    (new BaseResponse(false, ErrorMessages::EVALUATION_EMPTY_SCORES))->toArray(),
                    400
                );
            }

            foreach ($data['scores'] as $score) {

                if (!isset($score['score'])) {
                    return response()->json(
                        (new BaseResponse(false, 'Score field missing'))->toArray(),
                        400
                    );
                }

                if ($score['score'] < 0 || $score['score'] > 100) {
                    return response()->json(
                        (new BaseResponse(false, ErrorMessages::EVALUATION_INVALID_SCORE))->toArray(),
                        400
                    );
                }
            }

            $result = $this->service->createEvaluation($data, $coach->id);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::EVALUATION_CREATED, $result))->toArray(),
                201
            );

        } catch (\Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                500
            );
        }
    }
}