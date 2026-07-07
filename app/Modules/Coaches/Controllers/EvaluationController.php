<?php

namespace App\Modules\Coaches\Controllers;

use App\Modules\Coaches\Services\Interfaces\IEvaluationService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Throwable;

class EvaluationController extends Controller
{
    protected IEvaluationService $service;

    public function __construct(
        IEvaluationService $service
    ) {
        $this->service = $service;
    }

    public function createEvaluation(Request $request)
    {
        try {

            $user = Auth::user();

            if (
                $user->role !== 'coach' ||
                ! $user->coach
            ) {
                return response()->json(
                    (new BaseResponse(
                        false,
                        ErrorMessages::COACH_NOT_FOUND
                    ))->toArray(),
                    404
                );
            }

            $coach = $user->coach;

            $result = $this->service
                ->createEvaluation(
                    [
                        'title' => $request->title,
                        'date' => $request->date,
                    ],
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
            $user = Auth::user();
            $coach = ($user && $user->role === 'coach') ? $user->coach : null;

            if ($coach) {
                $result = $this->service->getEvaluationsByGroup($coach->group_id);
            } else {
                $result = $this->service->getAllEvaluations();
            }

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

            $result = $this->service
                ->getEvaluationById($id);

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

    public function updateEvaluation(
        Request $request,
        $id
    ) {
        try {

            $result = $this->service
                ->updateEvaluation(
                    $id,
                    $request->all()
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

    public function createEvaluationScores(
        Request $request
    ) {
        try {

            $result = $this->service
                ->createEvaluationScores(
                    $request->all()
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

    public function updateEvaluationScore(
        Request $request,
        $id
    ) {
        try {

            $result = $this->service
                ->updateEvaluationScore(
                    $id,
                    $request->all()
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

            $this->service
                ->deleteEvaluation($id);

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

    public function deleteEvaluationScore($id)
    {
        try {

            $this->service
                ->deleteEvaluationScore($id);

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
