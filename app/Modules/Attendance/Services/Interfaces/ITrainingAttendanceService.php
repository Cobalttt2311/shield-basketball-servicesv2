<?php

namespace App\Modules\Attendance\Services\Interfaces;

interface ITrainingAttendanceService
{
    public function getTrainingsForCoach(int $userId);

    public function getTrainingsForPlayer(int $userId);

    public function createTrainingForCoach(int $userId, array $data);

    public function updateTrainingForCoach(int $userId, int $trainingId, array $data);

    public function deleteTrainingForCoach(int $userId, int $trainingId);

    public function getPlayerAttendanceList(int $userId, int $trainingId);

    public function recordPlayerAttendance(int $userId, int $trainingId, array $attendancesData, bool $isFinal = false, ?string $coachStatus = null);

    public function getPlayerAttendanceSummary(int $userId);
}
