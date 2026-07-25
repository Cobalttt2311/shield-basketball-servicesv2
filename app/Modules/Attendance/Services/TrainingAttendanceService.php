<?php

namespace App\Modules\Attendance\Services;

use App\Modules\Admin\Repositories\Interfaces\IManagementDataRepository;
use App\Modules\Attendance\Repositories\Interfaces\ITrainingAttendanceRepository;
use App\Modules\Attendance\Services\Interfaces\ITrainingAttendanceService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use Exception;

class TrainingAttendanceService implements ITrainingAttendanceService
{
    public function __construct(
        protected ITrainingAttendanceRepository $repo,
        protected IManagementDataRepository $managementRepo
    ) {}

    public function getTrainingsForCoach(int $userId)
    {
        $coach = $this->managementRepo->findCoachByUserId($userId);
        if (! $coach) {
            throw new Exception(ErrorMessages::COACH_NOT_FOUND);
        }

        return $this->repo->getTrainingsByGroup($coach->group_id);
    }

    public function getTrainingsForPlayer(int $userId)
    {
        $player = $this->managementRepo->findPlayerByUserId($userId);
        if (! $player) {
            throw new Exception(ErrorMessages::PLAYER_NOT_FOUND);
        }

        return $this->repo->getTrainingsByGroup($player->group_id);
    }

    public function createTrainingForCoach(int $userId, array $data)
    {
        $coach = $this->managementRepo->findCoachByUserId($userId);
        if (! $coach) {
            throw new Exception(ErrorMessages::COACH_NOT_FOUND);
        }

        $data['group_id'] = $coach->group_id;

        return $this->repo->createTraining($data);
    }

    public function updateTrainingForCoach(int $userId, int $trainingId, array $data)
    {
        $coach = $this->managementRepo->findCoachByUserId($userId);
        if (! $coach) {
            throw new Exception(ErrorMessages::COACH_NOT_FOUND);
        }

        $training = $this->repo->findTraining($trainingId);
        if (! $training) {
            throw new Exception(ErrorMessages::TRAINING_NOT_FOUND);
        }

        if ($training->group_id !== $coach->group_id) {
            throw new Exception(ErrorMessages::TRAINING_FORBIDDEN_GROUP);
        }

        unset($data['group_id']); // Cannot change training group via update for security

        return $this->repo->updateTraining($trainingId, $data);
    }

    public function deleteTrainingForCoach(int $userId, int $trainingId)
    {
        $coach = $this->managementRepo->findCoachByUserId($userId);
        if (! $coach) {
            throw new Exception(ErrorMessages::COACH_NOT_FOUND);
        }

        $training = $this->repo->findTraining($trainingId);
        if (! $training) {
            throw new Exception(ErrorMessages::TRAINING_NOT_FOUND);
        }

        if ($training->group_id !== $coach->group_id) {
            throw new Exception(ErrorMessages::TRAINING_FORBIDDEN_GROUP);
        }

        return $this->repo->deleteTraining($trainingId);
    }

    public function getPlayerAttendanceList(int $userId, int $trainingId)
    {
        $coach = $this->managementRepo->findCoachByUserId($userId);
        if (! $coach) {
            throw new Exception(ErrorMessages::COACH_NOT_FOUND);
        }

        $training = $this->repo->findTraining($trainingId);
        if (! $training) {
            throw new Exception(ErrorMessages::TRAINING_NOT_FOUND);
        }

        if ($training->group_id !== $coach->group_id) {
            throw new Exception(ErrorMessages::TRAINING_FORBIDDEN_GROUP);
        }

        return $this->repo->getPlayerAttendanceList($trainingId);
    }

    public function recordPlayerAttendance(int $userId, int $trainingId, array $attendancesData, bool $isFinal = false, ?string $coachStatus = null)
    {
        $coach = $this->managementRepo->findCoachByUserId($userId);
        if (! $coach) {
            throw new Exception(ErrorMessages::COACH_NOT_FOUND);
        }

        $training = $this->repo->findTraining($trainingId);
        if (! $training) {
            throw new Exception(ErrorMessages::TRAINING_NOT_FOUND);
        }

        if ($training->group_id !== $coach->group_id) {
            throw new Exception(ErrorMessages::TRAINING_FORBIDDEN_GROUP);
        }

        $results = [];
        foreach ($attendancesData as $item) {
            // Basic validation: player_id is required
            if (! isset($item['player_id'])) {
                continue;
            }

            // Ensure player exists and is in the same group
            $player = $this->managementRepo->findPlayerById($item['player_id']);
            if (! $player || $player->group_id !== $training->group_id) {
                continue;
            }

            $results[] = $this->repo->upsertPlayerAttendance(
                $trainingId,
                $item['player_id'],
                $item['status'] ?? null,
                $item['description'] ?? null
            );
        }

        // Update finalized status, coach attendance, and logged coach
        $training->is_finalized = $isFinal;
        $training->coach_attendance_status = $coachStatus;
        $training->recorded_by_coach_id = $coach->id;
        $training->save();

        return $results;
    }

    public function getPlayerAttendanceSummary(int $userId)
    {
        $player = $this->managementRepo->findPlayerByUserId($userId);
        if (! $player) {
            throw new Exception(ErrorMessages::PLAYER_NOT_FOUND);
        }

        return $this->repo->getPlayerAttendanceSummary($player->id, $player->group_id);
    }
}
