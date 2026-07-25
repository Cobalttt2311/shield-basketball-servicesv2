<?php

namespace App\Modules\Attendance\Repositories\Interfaces;

use App\Modules\Attendance\Models\PlayerAttendance;
use App\Modules\Attendance\Models\Training;

interface ITrainingAttendanceRepository
{
    public function getTrainingsByGroup(int $groupId);

    public function findTraining(int $id): ?Training;

    public function createTraining(array $data): Training;

    public function updateTraining(int $id, array $data): ?Training;

    public function deleteTraining(int $id): bool;

    public function getPlayerAttendanceList(int $trainingId);

    public function upsertPlayerAttendance(int $trainingId, int $playerId, ?string $status, ?string $description = null): PlayerAttendance;

    public function getPlayerAttendanceSummary(int $playerId, int $groupId);
}
