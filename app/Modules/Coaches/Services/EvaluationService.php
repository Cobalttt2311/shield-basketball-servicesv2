<?php

namespace App\Modules\Coaches\Services;

use Illuminate\Support\Facades\DB;
use App\Modules\Coaches\Services\Interfaces\IEvaluationService;
use App\Modules\Coaches\Repositories\Interfaces\IEvaluationRepository;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaRepository;
use App\Modules\Admin\Repositories\Interfaces\IManagementDataRepository;

class EvaluationService implements IEvaluationService
{
    protected IEvaluationRepository $evaluationRepo;
    protected IManagementDataRepository $managementRepo;
    protected ICriteriaRepository $criteriaRepo;

    public function __construct(
        IEvaluationRepository $evaluationRepo,
        IManagementDataRepository $managementRepo,
        ICriteriaRepository $criteriaRepo
    ) {
        $this->evaluationRepo = $evaluationRepo;
        $this->managementRepo = $managementRepo;
        $this->criteriaRepo = $criteriaRepo;
    }

    public function createEvaluation(array $data, int $coachId)
    {
        return DB::transaction(function () use ($data, $coachId) {

            $coach = $this->managementRepo->findCoachById($coachId);
            if (!$coach) {
                throw new \Exception('Coach not found');
            }

            $evaluation = $this->evaluationRepo->createEvaluation([
                'title' => $data['title'],
                'date' => $data['date'],
                'coach_id' => $coachId
            ]);

            $scores = [];

            foreach ($data['scores'] as $item) {

                $player = $this->managementRepo->findPlayerById($item['player_id']);
                if (!$player || $player->group_id != $coach->group_id) {
                    throw new \Exception('Invalid player');
                }

                $sub = $this->criteriaRepo->findSubCriteriaWithCriteria($item['sub_criteria_id']);
                if (!$sub || !$sub->criteria || $sub->criteria->group_id != $player->group_id) {
                    throw new \Exception('Invalid sub criteria');
                }

                if ($item['score'] < 0 || $item['score'] > 100) {
                    throw new \Exception('Score must be 0-100');
                }

                $scores[] = [
                    'evaluation_id' => $evaluation->id,
                    'player_id' => $item['player_id'],
                    'sub_criteria_id' => $item['sub_criteria_id'],
                    'score' => $item['score'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $this->evaluationRepo->insertScores($scores);

            return $evaluation->load('scores');
        });
    }
}