<?php

namespace App\Modules\Attendance\Repositories;

use App\Modules\Admin\Models\Player;
use App\Modules\Attendance\Models\PlayerAttendance;
use App\Modules\Attendance\Models\Training;
use App\Modules\Attendance\Repositories\Interfaces\ITrainingAttendanceRepository;

class TrainingAttendanceRepository implements ITrainingAttendanceRepository
{
    public function getTrainingsByGroup(int $groupId)
    {
        return Training::where('group_id', $groupId)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();
    }

    public function findTraining(int $id): ?Training
    {
        return Training::find($id);
    }

    public function createTraining(array $data): Training
    {
        return Training::create($data);
    }

    public function updateTraining(int $id, array $data): ?Training
    {
        $training = Training::find($id);
        if (! $training) {
            return null;
        }
        $training->update($data);

        return $training;
    }

    public function deleteTraining(int $id): bool
    {
        $training = Training::find($id);
        if (! $training) {
            return false;
        }

        return $training->delete();
    }

    public function getPlayerAttendanceList(int $trainingId)
    {
        $training = Training::find($trainingId);
        if (! $training) {
            return null;
        }

        $players = Player::where('group_id', $training->group_id)
            ->orderBy('name', 'asc')
            ->get()
            ->map(function ($player) use ($trainingId) {
                $attendance = PlayerAttendance::where([
                    'training_id' => $trainingId,
                    'player_id' => $player->id,
                ])->first();

                return [
                    'id' => $player->id,
                    'name' => $player->name,
                    'email' => $player->email,
                    'attendance' => $attendance ? [
                        'status' => $attendance->status,
                        'description' => $attendance->description,
                        'attended_at' => $attendance->attended_at ? $attendance->attended_at->toDateTimeString() : null,
                    ] : null,
                ];
            });

        return [
            'training' => [
                'id' => $training->id,
                'title' => $training->title,
                'date' => $training->date,
                'start_time' => $training->start_time,
                'end_time' => $training->end_time,
                'group_id' => $training->group_id,
                'is_finalized' => $training->is_finalized,
                'coach_attendance_status' => $training->coach_attendance_status,
                'recorded_by_coach_id' => $training->recorded_by_coach_id,
                'recorded_by_coach_name' => $training->recordedBy ? $training->recordedBy->name : null,
            ],
            'players' => $players,
        ];
    }

    public function upsertPlayerAttendance(int $trainingId, int $playerId, ?string $status, ?string $description = null): PlayerAttendance
    {
        return PlayerAttendance::updateOrCreate(
            [
                'training_id' => $trainingId,
                'player_id' => $playerId,
            ],
            [
                'status' => $status,
                'description' => $description,
                'attended_at' => $status === 'present' ? now() : null,
            ]
        );
    }

    public function getPlayerAttendanceSummary(int $playerId, int $groupId)
    {
        $allTrainings = Training::where('group_id', $groupId)
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->get();

        $finalizedTrainings = $allTrainings->where('is_finalized', true);
        $totalMeetings = $finalizedTrainings->count();

        $finalizedTrainingIds = $finalizedTrainings->pluck('id')->toArray();
        $attendances = PlayerAttendance::where('player_id', $playerId)
            ->whereIn('training_id', $finalizedTrainingIds)
            ->get();
        $totalPresent = $attendances->where('status', 'present')->count();
        $totalAbsent = $attendances->where('status', 'absent')->count();

        $logs = $allTrainings->map(function ($t) use ($playerId) {
            $att = PlayerAttendance::where([
                'training_id' => $t->id,
                'player_id' => $playerId,
            ])->first();

            return [
                'training_id' => $t->id,
                'title' => $t->title,
                'date' => $t->date,
                'start_time' => $t->start_time,
                'end_time' => $t->end_time,
                'status' => $t->is_finalized
                    ? ($att ? ($att->status ?? 'not_recorded') : 'not_recorded')
                    : 'not_recorded',
                'description' => $t->is_finalized ? ($att ? $att->description : null) : null,
                'attended_at' => $t->is_finalized && $att && $att->attended_at ? $att->attended_at->toDateTimeString() : null,
                'recorded_by_coach_name' => $t->recordedBy ? $t->recordedBy->name : null,
            ];
        });

        $attendancePercentage = $totalMeetings > 0 ? round(($totalPresent / $totalMeetings) * 100, 2) : 0;

        return [
            'summary' => [
                'total_meetings' => $totalMeetings,
                'total_present' => $totalPresent,
                'total_absent' => $totalAbsent,
                'attendance_percentage' => $attendancePercentage,
            ],
            'logs' => $logs,
        ];
    }
}
