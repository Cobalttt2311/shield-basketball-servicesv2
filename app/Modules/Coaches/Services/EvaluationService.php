<?php

namespace App\Modules\Coaches\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use App\Modules\Coaches\Services\Interfaces\IEvaluationService;
use App\Modules\Coaches\Repositories\Interfaces\IEvaluationRepository;

class EvaluationService implements IEvaluationService
{
    protected IEvaluationRepository $repo;

    public function __construct(
        IEvaluationRepository $repo
    ) {
        $this->repo = $repo;
    }

    public function createEvaluation(
        array $data,
        int $coachId
    ) {

        $evaluation = $this->repo->createEvaluation([
            'title' => $data['title'],
            'date' => $data['date'],
            'coach_id' => $coachId
        ]);

        return $this->repo
            ->getEvaluationById($evaluation->id);
    }

    public function getAllEvaluations()
    {
        return $this->repo->getAllEvaluations();
    }

    public function getEvaluationById(int $id)
    {
        $evaluation = $this->repo
            ->getEvaluationById($id);

        if (!$evaluation) {
            throw new Exception(
                ErrorMessages::EVALUATION_NOT_FOUND
            );
        }

        return $evaluation;
    }

    public function updateEvaluation(
        int $id,
        array $data
    ) {

        $evaluation = $this->repo
            ->getEvaluationById($id);

        if (!$evaluation) {
            throw new Exception(
                ErrorMessages::EVALUATION_NOT_FOUND
            );
        }

        $payload = [];

        if (isset($data['title'])) {
            $payload['title'] = $data['title'];
        }

        if (isset($data['date'])) {
            $payload['date'] = $data['date'];
        }

        return $this->repo->updateEvaluation(
            $id,
            $payload
        );
    }

    public function createEvaluationScores(array $data)
    {
        DB::beginTransaction();

        try {

            $evaluation = $this->repo
                ->getEvaluationById(
                    $data['evaluation_id']
                );

            if (!$evaluation) {
                throw new Exception(
                    ErrorMessages::EVALUATION_NOT_FOUND
                );
            }

            $scores = [];

            foreach ($data['scores'] as $score) {

                $subCriteria = $this->repo
                    ->findSubCriteriaWithCriteria(
                        $score['sub_criteria_id']
                    );

                if (!$subCriteria) {
                    throw new Exception(
                        ErrorMessages::SUBCRITERIA_NOT_FOUND
                    );
                }

                if (
                    $score['score'] < 0 ||
                    $score['score'] > 100
                ) {
                    throw new Exception(
                        ErrorMessages::EVALUATION_INVALID_SCORE
                    );
                }

                $scores[] = [
                    'evaluation_id' => $data['evaluation_id'],
                    'player_id' => $score['player_id'],
                    'sub_criteria_id' => $score['sub_criteria_id'],
                    'score' => $score['score'],
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }

            $this->repo->insertScores($scores);

            DB::commit();

            return $this->repo
                ->getEvaluationById(
                    $data['evaluation_id']
                );

        } catch (Exception $e) {

            DB::rollBack();

            throw $e;
        }
    }

    public function updateEvaluationScore(
        int $id,
        array $data
    ) {

        $score = $this->repo
            ->findScoreById($id);

        if (!$score) {
            throw new Exception(
                'Evaluation score not found'
            );
        }

        $payload = [];

        if (isset($data['player_id'])) {
            $payload['player_id']
                = $data['player_id'];
        }

        if (isset($data['sub_criteria_id'])) {

            $subCriteria = $this->repo
                ->findSubCriteriaWithCriteria(
                    $data['sub_criteria_id']
                );

            if (!$subCriteria) {
                throw new Exception(
                    ErrorMessages::SUBCRITERIA_NOT_FOUND
                );
            }

            $payload['sub_criteria_id']
                = $data['sub_criteria_id'];
        }

        if (isset($data['score'])) {

            if (
                $data['score'] < 0 ||
                $data['score'] > 100
            ) {
                throw new Exception(
                    ErrorMessages::EVALUATION_INVALID_SCORE
                );
            }

            $payload['score']
                = $data['score'];
        }

        return $this->repo->updateScore(
            $id,
            $payload
        );
    }

    public function deleteEvaluation(int $id)
    {
        $evaluation = $this->repo
            ->getEvaluationById($id);

        if (!$evaluation) {
            throw new Exception(
                ErrorMessages::EVALUATION_NOT_FOUND
            );
        }

        return $this->repo
            ->deleteEvaluation($id);
    }

    public function deleteEvaluationScore(int $id)
    {
        $score = $this->repo
            ->findScoreById($id);

        if (!$score) {
            throw new Exception(
                'Evaluation score not found'
            );
        }

        return $this->repo
            ->deleteScore($id);
    }
}