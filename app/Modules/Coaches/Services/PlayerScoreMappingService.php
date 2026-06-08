<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Coaches\Repositories\Interfaces\IPlayerScoreMappingRepository;
use App\Modules\Coaches\Services\Interfaces\IAhpCalculationService;
use App\Modules\Coaches\Services\Interfaces\IPlayerScoreMappingService;

class PlayerScoreMappingService
    implements IPlayerScoreMappingService
{
    protected $repository;

    protected $ahpService;

    public function __construct(
        IPlayerScoreMappingRepository $repository,
        IAhpCalculationService $ahpService
    ) {
        $this->repository = $repository;
        $this->ahpService = $ahpService;
    }

    public function calculateAlternativeWeights(
        int $evaluationId,
        int $subCriteriaId
    ) {

        $scores =
            $this->repository
                ->getScoresBySubCriteria(
                    $evaluationId,
                    $subCriteriaId
                );

        if ($scores->count() === 0) {

            return [];
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
                }

                else {

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

                'player_id' =>
                    $score->player_id,

                'player_name' =>
                    $score->player->name,

                'raw_score' =>
                    $score->score,

                'normalized_score' =>
                    $scaledValues[$index],

                'eigen_vector' =>
                    $weights[$index]
            ];
        }

        return [

            'players' => $result,

            'consistency' =>
                $consistency
        ];
    }
}