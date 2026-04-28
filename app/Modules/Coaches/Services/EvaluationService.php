<?php

namespace App\Modules\Coaches\Services;

use Illuminate\Support\Facades\DB;
use App\Modules\Coaches\Services\Interfaces\IEvaluationService;
use App\Modules\Coaches\Repositories\Interfaces\IEvaluationRepository;
use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Player;
use App\Modules\Coaches\Models\SubCriteria;

class EvaluationService implements IEvaluationService
{
    protected IEvaluationRepository $repo;

    public function __construct(IEvaluationRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createEvaluation(array $data, int $coachId)
    {
        return DB::transaction(function () use ($data, $coachId) {

            $coach = Coach::find($coachId);
            if (!$coach) {
                throw new \Exception('Coach not found');
            }

            $evaluation = $this->repo->createEvaluation([
                'title' => $data['title'],
                'date' => $data['date'],
                'coach_id' => $coachId
            ]);

            $scores = [];

            foreach ($data['scores'] as $item) {

                $player = Player::find($item['player_id']);
                if (!$player || $player->group_id != $coach->group_id) {
                    throw new \Exception('Invalid player');
                }

                $sub = SubCriteria::find($item['sub_criteria_id']);
                if (!$sub || $sub->group_id != $coach->group_id) {
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

            $this->repo->insertScores($scores);

            return $evaluation->load('scores');
        });
    }
}