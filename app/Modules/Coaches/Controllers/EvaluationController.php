<?php

namespace App\Modules\Coaches\Controllers;

use Throwable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Utils\Responses\BaseResponse;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Modules\Coaches\Services\Interfaces\IEvaluationService;

class EvaluationController extends Controller
{
    protected IEvaluationService $service;

    public function __construct(IEvaluationService $service)
    {
        $this->service = $service;
    }

    public function createEvaluation(Request $request)
    {
        try {

            $user = Auth::user();

            if ($user->role !== 'coach' || !$user->coach) {
                return response()->json(
                    (new BaseResponse(
                        false,
                        ErrorMessages::COACH_NOT_FOUND
                    ))->toArray(),
                    404
                );
            }

            $coach = $user->coach;

            $data = $request->all();

            if (empty($data['scores']) || !is_array($data['scores'])) {
                return response()->json(
                    (new BaseResponse(
                        false,
                        ErrorMessages::EVALUATION_EMPTY_SCORES
                    ))->toArray(),
                    400
                );
            }

            foreach ($data['scores'] as $score) {

                if (!isset($score['score'])) {
                    return response()->json(
                        (new BaseResponse(
                            false,
                            ErrorMessages::EVALUATION_INVALID_SCORE
                        ))->toArray(),
                        400
                    );
                }

                if ($score['score'] < 0 || $score['score'] > 100) {
                    return response()->json(
                        (new BaseResponse(
                            false,
                            ErrorMessages::EVALUATION_INVALID_SCORE
                        ))->toArray(),
                        400
                    );
                }
            }

            $result = $this->service->createEvaluation(
                $data,
                $coach->id
            );

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::EVALUATION_CREATED,
                    $result
                ))->toArray(),
                201
            );

        } catch (Throwable $e) {

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function getAllEvaluations()
    {
        try {

            $result = $this->service->getAllEvaluations();

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::EVALUATION_GET,
                    $result
                ))->toArray()
            );

        } catch (Throwable $e) {

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function getEvaluationById($id)
    {
        try {

            $result = $this->service->getEvaluationById($id);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::EVALUATION_GET,
                    $result
                ))->toArray()
            );

        } catch (Throwable $e) {

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function updateEvaluation(Request $request, $id)
    {
        try {

            $data = $request->all();

            if (isset($data['scores'])) {

                if (!is_array($data['scores'])) {
                    return response()->json(
                        (new BaseResponse(
                            false,
                            ErrorMessages::EVALUATION_EMPTY_SCORES
                        ))->toArray(),
                        400
                    );
                }

                foreach ($data['scores'] as $score) {

                    if (!isset($score['score'])) {
                        return response()->json(
                            (new BaseResponse(
                                false,
                                ErrorMessages::EVALUATION_INVALID_SCORE
                            ))->toArray(),
                            400
                        );
                    }

                    if ($score['score'] < 0 || $score['score'] > 100) {
                        return response()->json(
                            (new BaseResponse(
                                false,
                                ErrorMessages::EVALUATION_INVALID_SCORE
                            ))->toArray(),
                            400
                        );
                    }
                }
            }

            $result = $this->service->updateEvaluation(
                $id,
                $data
            );

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::EVALUATION_UPDATED,
                    $result
                ))->toArray()
            );

        } catch (Throwable $e) {

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }

    public function deleteEvaluation($id)
    {
        try {

            $this->service->deleteEvaluation($id);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::EVALUATION_DELETED
                ))->toArray()
            );

        } catch (Throwable $e) {

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                500
            );
        }
    }
}