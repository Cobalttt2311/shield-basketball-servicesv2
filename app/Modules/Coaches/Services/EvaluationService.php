<?php

namespace App\Modules\Coaches\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Modules\Coaches\Services\Interfaces\IEvaluationService;
use App\Modules\Coaches\Repositories\Interfaces\IEvaluationRepository;
use App\Utils\Messages\ErrorMessages\ErrorMessages;

class EvaluationService implements IEvaluationService
{
    protected IEvaluationRepository $repo;

    public function __construct(IEvaluationRepository $repo)
    {
        $this->repo = $repo;
    }

    public function createEvaluation(array $data, int $coachId)
    {
        DB::beginTransaction();

        try {

            $evaluation = $this->repo->createEvaluation([
                'title' => $data['title'],
                'date' => $data['date'],
                'coach_id' => $coachId
            ]);

            $scores = [];

            foreach ($data['scores'] as $score) {

                $subCriteria = $this->repo
                    ->findSubCriteriaWithCriteria($score['sub_criteria_id']);

                if (!$subCriteria) {
                    throw new Exception('Sub criteria not found');
                }

                $scores[] = [
                    'evaluation_id' => $evaluation->id,
                    'player_id' => $score['player_id'],
                    'sub_criteria_id' => $score['sub_criteria_id'],
                    'score' => $score['score'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $this->repo->insertScores($scores);

            DB::commit();

            return $this->repo->getEvaluationById($evaluation->id);

        } catch (Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function getAllEvaluations()
    {
        return $this->repo->getAllEvaluations();
    }

    public function getEvaluationById(int $id)
    {
        $evaluation = $this->repo->getEvaluationById($id);

        if (!$evaluation) {
            throw new Exception('Evaluation not found');
        }

        return $evaluation;
    }

    public function updateEvaluation(int $id, array $data)
    {
        DB::beginTransaction();

        try {

            $evaluation = $this->repo->getEvaluationById($id);

            if (!$evaluation) {
                throw new Exception('Evaluation not found');
            }

            $updatedEvaluation = $this->repo->updateEvaluation($id, [
                'title' => $data['title'],
                'date' => $data['date']
            ]);

            if (isset($data['scores']) && is_array($data['scores'])) {

                $this->repo->deleteScoresByEvaluation($id);

                $scores = [];

                foreach ($data['scores'] as $score) {

                    $subCriteria = $this->repo
                        ->findSubCriteriaWithCriteria(
                            $score['sub_criteria_id']
                        );

                    if (!$subCriteria) {
                        throw new Exception('Sub criteria not found');
                    }

                    $scores[] = [
                        'evaluation_id' => $id,
                        'player_id' => $score['player_id'],
                        'sub_criteria_id' => $score['sub_criteria_id'],
                        'score' => $score['score'],
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                $this->repo->insertScores($scores);
            }

            DB::commit();

            return $this->repo->getEvaluationById($id);

        } catch (Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function deleteEvaluation(int $id)
    {
        $evaluation = $this->repo->getEvaluationById($id);

        if (!$evaluation) {
            throw new Exception(ErrorMessages::EVALUATION_NOT_FOUND);
        }

        return $this->repo->deleteEvaluation($id);
    }
}