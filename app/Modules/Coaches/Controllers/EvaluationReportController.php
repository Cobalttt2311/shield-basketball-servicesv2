<?php

namespace App\Modules\Coaches\Controllers;

use App\Modules\Coaches\Services\Interfaces\IEvaluationReportService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Throwable;

class EvaluationReportController extends Controller
{
    protected IEvaluationReportService $service;

    public function __construct(IEvaluationReportService $service)
    {
        $this->service = $service;
    }

    public function finalizeReport(Request $request)
    {
        try {
            $request->validate([
                'evaluation_id' => 'required|integer',
                'player_id' => 'required|integer',
                'recommended_position_id' => 'required|integer',
                'final_position_id' => 'required|integer',
                'notes' => 'string|nullable',
            ]);

            $result = $this->service->finalizeReport($request->only([
                'evaluation_id',
                'player_id',
                'recommended_position_id',
                'final_position_id',
                'notes',
            ]));

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::REPORT_FINALIZED,
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
                400
            );
        }
    }

    public function getFinalizedReport($evaluationId, $playerId)
    {
        try {
            $result = $this->service->getReportByEvaluationAndPlayer(
                (int) $evaluationId,
                (int) $playerId,
                true
            );

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::REPORT_GET,
                    $result
                ))->toArray()
            );
        } catch (Throwable $e) {
            $statusCode = $e->getMessage() === ErrorMessages::REPORT_NOT_FOUND ? 404 : 400;

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                $statusCode
            );
        }
    }

    public function getPlayerReport($evaluationId)
    {
        try {
            $user = Auth::user();
            if (! $user || $user->role !== 'player' || ! $user->player) {
                return response()->json(
                    (new BaseResponse(
                        false,
                        ErrorMessages::PLAYER_NOT_FOUND,
                        null,
                        'Unauthorized player access'
                    ))->toArray(),
                    403
                );
            }

            $playerId = $user->player->id;
            $result = $this->service->getReportByEvaluationAndPlayer(
                (int) $evaluationId,
                $playerId
            );

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::REPORT_GET,
                    $result
                ))->toArray()
            );
        } catch (Throwable $e) {
            $statusCode = $e->getMessage() === ErrorMessages::REPORT_NOT_FOUND ? 404 : 400;

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                $statusCode
            );
        }
    }

    public function downloadPlayerReportPdf($evaluationId)
    {
        try {
            $user = Auth::user();
            if (! $user || $user->role !== 'player' || ! $user->player) {
                return response()->json(
                    (new BaseResponse(
                        false,
                        ErrorMessages::PLAYER_NOT_FOUND,
                        null,
                        'Unauthorized player access'
                    ))->toArray(),
                    403
                );
            }

            $playerId = $user->player->id;
            $pdf = $this->service->generatePdfReport((int) $evaluationId, $playerId);

            return $pdf->download("report-{$evaluationId}-{$playerId}.pdf");
        } catch (Throwable $e) {
            $statusCode = $e->getMessage() === ErrorMessages::REPORT_NOT_FOUND ? 404 : 400;

            return response()->json(
                (new BaseResponse(
                    false,
                    $e->getMessage(),
                    null,
                    $e->getMessage()
                ))->toArray(),
                $statusCode
            );
        }
    }

    public function getMyReportsList()
    {
        try {
            $user = Auth::user();
            if (! $user || $user->role !== 'player' || ! $user->player) {
                return response()->json(
                    (new BaseResponse(
                        false,
                        ErrorMessages::PLAYER_NOT_FOUND,
                        null,
                        'Unauthorized player access'
                    ))->toArray(),
                    403
                );
            }

            $playerId = $user->player->id;
            $result = $this->service->getReportsListForPlayer($playerId);

            return response()->json(
                (new BaseResponse(
                    true,
                    SuccessMessages::REPORT_GET,
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
                400
            );
        }
    }

    public function getPlayersForFinalization($evaluationId)
    {
        try {
            $data = $this->service->getPlayersForFinalization((int) $evaluationId);

            return response()->json(
                (new BaseResponse(
                    true,
                    'Evaluation players retrieved successfully',
                    $data
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
                400
            );
        }
    }
}
