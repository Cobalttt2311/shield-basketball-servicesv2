<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Coaches\Models\Evaluation;
use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaWeightRepository;
use App\Modules\Coaches\Repositories\Interfaces\IPlayerScoreMappingRepository;
use App\Modules\Coaches\Repositories\Interfaces\ISubCriteriaWeightRepository;
use App\Modules\Coaches\Services\Interfaces\IAhpCalculationService;
use App\Modules\Coaches\Services\Interfaces\IPlayerScoreMappingService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;

class PlayerScoreMappingService implements IPlayerScoreMappingService
{
    protected $repository;

    protected $ahpService;

    protected $criteriaWeightRepo;

    protected $subCriteriaWeightRepo;

    public function __construct(
        IPlayerScoreMappingRepository $repository,
        IAhpCalculationService $ahpService,
        ICriteriaWeightRepository $criteriaWeightRepo,
        ISubCriteriaWeightRepository $subCriteriaWeightRepo
    ) {
        $this->repository = $repository;
        $this->ahpService = $ahpService;
        $this->criteriaWeightRepo = $criteriaWeightRepo;
        $this->subCriteriaWeightRepo = $subCriteriaWeightRepo;
    }

    public function calculateAlternativeWeights(
        int $evaluationId,
        int $subCriteriaId
    ) {
        $evaluationExists = Evaluation::where('id', $evaluationId)->exists();
        if (! $evaluationExists) {
            throw new \InvalidArgumentException(ErrorMessages::EVALUATION_NOT_FOUND);
        }

        $subCriteriaExists = SubCriteria::where('id', $subCriteriaId)->exists();
        if (! $subCriteriaExists) {
            throw new \InvalidArgumentException(ErrorMessages::SUBCRITERIA_NOT_FOUND);
        }

        $scores =
            $this->repository
                ->getScoresBySubCriteria(
                    $evaluationId,
                    $subCriteriaId
                );

        if ($scores->count() === 0) {
            throw new \InvalidArgumentException(ErrorMessages::EVALUATION_SCORES_NOT_FOUND);
        }

        $rawScores =
            $scores
                ->pluck('score')
                ->toArray();

        $min =
            min($rawScores);

        $max =
            max($rawScores);

        /**
         * Min-Max -> Saaty
         */
        $scaledValues = [];

        foreach ($scores as $score) {

            $scaledValues[] =

                $this->ahpService
                    ->minMaxToSaaty(

                        $score->score,

                        $min,

                        $max
                    );
        }

        /**
         * Pairwise Matrix
         */
        $matrix = [];

        $size =
            count($scaledValues);

        for ($i = 0; $i < $size; $i++) {

            for ($j = 0; $j < $size; $j++) {

                if ($i === $j) {

                    $matrix[$i][$j] = 1;
                } else {

                    $matrix[$i][$j] =

                        round(
                            $scaledValues[$i]
                            /
                            $scaledValues[$j],
                            8
                        );
                }
            }
        }

        /**
         * Eigen Vector
         */
        $weights =

            $this->ahpService
                ->calculateEigenVector(
                    $matrix
                );

        /**
         * CR
         */
        $consistency =

            $this->ahpService
                ->calculateConsistency(
                    $matrix,
                    $weights
                );

        $result = [];

        foreach (
            $scores as $index => $score
        ) {

            $result[] = [

                'player_id' => $score->player_id,

                'player_name' => $score->player->name,

                'raw_score' => $score->score,

                'normalized_score' => $scaledValues[$index],

                'eigen_vector' => $weights[$index],
            ];
        }

        return [

            'players' => $result,

            'consistency' => $consistency,
        ];
    }

    public function calculateAlternativeScores(
        int $evaluationId,
        int $positionId,
        ?int $pairwiseSetId = null
    ): array {
        $evaluation = Evaluation::find($evaluationId);
        if (! $evaluation) {
            throw new \InvalidArgumentException(ErrorMessages::EVALUATION_NOT_FOUND);
        }

        $positionExists = Position::where('id', $positionId)->exists();
        if (! $positionExists) {
            throw new \InvalidArgumentException(ErrorMessages::POSITION_NOT_FOUND);
        }

        if ($pairwiseSetId === null) {
            $pairwiseSetId = $evaluation->pairwise_set_id;
        }

        $criteriaWeights = $this->criteriaWeightRepo->getByPosition($positionId, $pairwiseSetId);
        if ($criteriaWeights->isEmpty()) {
            throw new \InvalidArgumentException(ErrorMessages::CRITERIA_WEIGHTS_NOT_FOUND);
        }

        $subCriteriaWeights = $this->subCriteriaWeightRepo->getByPosition($positionId, $pairwiseSetId);
        if ($subCriteriaWeights->isEmpty()) {
            throw new \InvalidArgumentException(ErrorMessages::SUBCRITERIA_WEIGHTS_NOT_FOUND);
        }

        $globalWeights = [];
        foreach ($subCriteriaWeights as $scw) {
            $subCriteria = $scw->subCriteria;
            if (! $subCriteria) {
                continue;
            }
            $criteriaId = $subCriteria->criteria_id;
            $cw = $criteriaWeights->where('criteria_id', $criteriaId)->first();
            $criteriaWeightVal = $cw ? $cw->weight : 0.0;
            $globalWeights[$scw->sub_criteria_id] = $criteriaWeightVal * $scw->weight;
        }

        $playerScores = [];

        foreach ($globalWeights as $subCriteriaId => $globalWeight) {
            $altWeightsData = $this->calculateAlternativeWeights($evaluationId, $subCriteriaId);
            if (empty($altWeightsData) || ! isset($altWeightsData['players'])) {
                continue;
            }

            foreach ($altWeightsData['players'] as $playerWeight) {
                $pId = $playerWeight['player_id'];
                $pName = $playerWeight['player_name'];
                $eigenVector = $playerWeight['eigen_vector'];

                if (! isset($playerScores[$pId])) {
                    $playerScores[$pId] = [
                        'player_id' => $pId,
                        'player_name' => $pName,
                        'score' => 0.0,
                    ];
                }
                $playerScores[$pId]['score'] += $globalWeight * $eigenVector;
            }
        }

        // Urutkan berdasarkan score terbesar
        usort($playerScores, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return $playerScores;
    }

    public function getPositionRecommendations(
        int $evaluationId
    ): array {
        $evaluation = Evaluation::find($evaluationId);
        if (! $evaluation) {
            throw new \InvalidArgumentException(ErrorMessages::EVALUATION_NOT_FOUND);
        }
        $pairwiseSetId = $evaluation->pairwise_set_id;
        $positions = Position::all();
        $allRecommendations = [];

        foreach ($positions as $position) {
            try {
                $scores = $this->calculateAlternativeScores($evaluationId, $position->id, $pairwiseSetId);
                foreach ($scores as $playerScore) {
                    $pId = $playerScore['player_id'];
                    $pName = $playerScore['player_name'];
                    $score = $playerScore['score'];

                    if (! isset($allRecommendations[$pId])) {
                        $allRecommendations[$pId] = [
                            'player_id' => $pId,
                            'player_name' => $pName,
                            'positions' => [],
                        ];
                    }

                    $allRecommendations[$pId]['positions'][] = [
                        'position_id' => $position->id,
                        'position_name' => $position->name,
                        'score' => round($score, 6),
                    ];
                }
            } catch (\InvalidArgumentException $e) {
                // Skip positions that don't have criteria weights, subcriteria weights, or evaluation scores
                continue;
            }
        }

        foreach ($allRecommendations as $pId => &$rec) {
            usort($rec['positions'], function ($a, $b) {
                return $b['score'] <=> $a['score'];
            });
            $rec['positions'] = array_slice($rec['positions'], 0, 3);
        }
        unset($rec);

        return array_values($allRecommendations);
    }
}
