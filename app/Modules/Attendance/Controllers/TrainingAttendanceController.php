<?php

namespace App\Modules\Attendance\Controllers;

use App\Modules\Attendance\Services\Interfaces\ITrainingAttendanceService;
use App\Utils\Messages\SuccessMessages\SuccessMessages;
use App\Utils\Responses\BaseResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Throwable;

class TrainingAttendanceController extends Controller
{
    public function __construct(
        protected ITrainingAttendanceService $service
    ) {}

    public function getTrainingsForCoach()
    {
        try {
            $user = Auth::user();
            $trainings = $this->service->getTrainingsForCoach($user->id);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::TRAINING_GET, $trainings))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                400
            );
        }
    }

    public function createTrainingForCoach(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'date' => 'required|date',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            $user = Auth::user();
            $training = $this->service->createTrainingForCoach($user->id, $validated);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::TRAINING_CREATED, $training))->toArray(),
                201
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                400
            );
        }
    }

    public function updateTrainingForCoach(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'date' => 'sometimes|required|date',
                'start_time' => 'sometimes|required',
                'end_time' => 'sometimes|required',
            ]);

            $user = Auth::user();
            $training = $this->service->updateTrainingForCoach($user->id, (int) $id, $validated);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::TRAINING_UPDATED, $training))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                400
            );
        }
    }

    public function deleteTrainingForCoach($id)
    {
        try {
            $user = Auth::user();
            $this->service->deleteTrainingForCoach($user->id, (int) $id);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::TRAINING_DELETED))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                400
            );
        }
    }

    public function getPlayerAttendanceList($id)
    {
        try {
            $user = Auth::user();
            $data = $this->service->getPlayerAttendanceList($user->id, (int) $id);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::TRAINING_ATTENDANCE_GET, $data))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                400
            );
        }
    }

    public function recordPlayerAttendance(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'is_final' => 'sometimes|boolean',
                'coach_status' => 'nullable|string|in:present,absent',
                'attendances' => 'required|array',
                'attendances.*.player_id' => 'required|integer|exists:players,id',
                'attendances.*.status' => 'nullable|string|in:present,absent',
                'attendances.*.description' => 'nullable|string',
            ]);

            $user = Auth::user();
            $isFinal = (bool) $request->input('is_final', false);
            $coachStatus = $request->input('coach_status');
            $result = $this->service->recordPlayerAttendance($user->id, (int) $id, $validated['attendances'], $isFinal, $coachStatus);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::TRAINING_ATTENDANCE_SAVED, $result))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                400
            );
        }
    }

    public function getPlayerAttendanceSummary()
    {
        try {
            $user = Auth::user();
            $summary = $this->service->getPlayerAttendanceSummary($user->id);

            return response()->json(
                (new BaseResponse(true, SuccessMessages::PLAYER_ATTENDANCE_SUMMARY_GET, $summary))->toArray()
            );
        } catch (Throwable $e) {
            return response()->json(
                (new BaseResponse(false, $e->getMessage(), null, $e->getMessage()))->toArray(),
                400
            );
        }
    }
}
