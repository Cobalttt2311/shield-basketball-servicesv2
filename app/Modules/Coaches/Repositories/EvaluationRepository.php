<?php

namespace App\Modules\Coaches\Repositories;

use App\Modules\Coaches\Models\Evaluation;
use App\Modules\Coaches\Models\EvaluationScore;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Repositories\Interfaces\IEvaluationRepository;

class EvaluationRepository implements IEvaluationRepository
{
    public function findSubCriteriaWithCriteria(int $id)
    {
        return SubCriteria::with('criteria')->find($id);
    }

    public function createEvaluation(array $data)
    {
        return Evaluation::create($data);
    }

    public function insertScores(array $scores)
    {
        return EvaluationScore::insert($scores);
    }

    public function getAllEvaluations()
    {
        return Evaluation::with([
            'coach',
            'scores',
        ])->get();
    }

    public function getEvaluationById(int $id)
    {
        return Evaluation::with([
            'coach',
            'scores',
        ])->find($id);
    }

    public function updateEvaluation(
        int $id,
        array $data
    ) {

        /** @var Evaluation|null $evaluation */
        $evaluation = Evaluation::find($id);

        if (! $evaluation) {
            return null;
        }

        $evaluation->update($data);

        return $evaluation->load([
            'coach',
            'scores',
        ]);
    }

    public function deleteEvaluation(int $id)
    {
        return Evaluation::destroy($id);
    }

    public function findScoreById(int $id)
    {
        return EvaluationScore::find($id);
    }

    public function updateScore(
        int $id,
        array $data
    ) {

        /** @var EvaluationScore|null $score */
        $score = EvaluationScore::find($id);

        if (! $score) {
            return null;
        }

        $score->update($data);

        return $score;
    }

    public function deleteScore(int $id)
    {
        return EvaluationScore::destroy($id);
    }
}
