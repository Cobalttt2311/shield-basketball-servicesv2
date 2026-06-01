<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Coaches\Repositories\Interfaces\IPairwiseCriteriaRepository;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaWeightRepository;
use App\Modules\Coaches\Services\Interfaces\IPairwiseCriteriaService;

class PairwiseCriteriaService implements IPairwiseCriteriaService
{
    protected $pairwiseRepository;
    protected $weightRepository;

    public function __construct(
        IPairwiseCriteriaRepository $pairwiseRepository,
        ICriteriaWeightRepository $weightRepository
    ) {
        $this->pairwiseRepository = $pairwiseRepository;
        $this->weightRepository = $weightRepository;
    }

    /**
     * Generate Pairwise Otomatis
     */
    public function generatePairwise(
        int $groupId,
        int $positionId
    ) {
        $criteria =
            $this->pairwiseRepository
            ->getCriteriaByGroup($groupId);

        $this->pairwiseRepository
            ->deleteByPosition($positionId);

        $data = [];

        $count = count($criteria);

        for ($i = 0; $i < $count; $i++) {

            for ($j = $i + 1; $j < $count; $j++) {

                $data[] = [
                    'position_id' => $positionId,
                    'criteria_first_id' =>
                        $criteria[$i]->id,
                    'criteria_second_id' =>
                        $criteria[$j]->id,
                    'value' => null,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        if (!empty($data)) {
            $this->pairwiseRepository
                ->insertMany($data);
        }

        return true;
    }

    /**
     * Update Value
     */
    public function updateValue(
        array $data
    ) {
        return $this->pairwiseRepository
            ->updateValue(
                $data['position_id'],
                $data['criteria_first_id'],
                $data['criteria_second_id'],
                $data['value']
            );
    }

    /**
     * Generate Matrix
     * Reciprocal Otomatis
     */
    public function generateMatrix(
        int $groupId,
        int $positionId
    ) {
        $criteria =
            $this->pairwiseRepository
            ->getCriteriaByGroup($groupId);

        $matrix = [];

        $size = count($criteria);

        for ($i = 0; $i < $size; $i++) {

            for ($j = 0; $j < $size; $j++) {

                if ($i === $j) {

                    $matrix[$i][$j] = 1;
                }

                elseif ($i < $j) {

                    $pairwise =
                        $this->pairwiseRepository
                        ->getValue(
                            $positionId,
                            $criteria[$i]->id,
                            $criteria[$j]->id
                        );

                    $matrix[$i][$j] =
                        $pairwise?->value ?? 0;
                }

                else {

                    $pairwise =
                        $this->pairwiseRepository
                        ->getValue(
                            $positionId,
                            $criteria[$j]->id,
                            $criteria[$i]->id
                        );

                    $matrix[$i][$j] =
                        $pairwise && $pairwise->value
                        ? 1 / $pairwise->value
                        : 0;
                }
            }
        }

        return [
            'criteria' => $criteria,
            'matrix' => $matrix
        ];
    }

    /**
     * Geometric Mean
     */
    public function calculateWeights(
        int $groupId,
        int $positionId
    ) {
        $generated =
            $this->generateMatrix(
                $groupId,
                $positionId
            );

        $matrix =
            $generated['matrix'];

        $criteria =
            $generated['criteria'];

        $size = count($matrix);

        $geometricMeans = [];

        for ($i = 0; $i < $size; $i++) {

            $product = 1;

            for ($j = 0; $j < $size; $j++) {

                $product *= $matrix[$i][$j];
            }

            $geometricMeans[$i] =
                pow(
                    $product,
                    1 / $size
                );
        }

        $totalGM =
            array_sum(
                $geometricMeans
            );

        $weights = [];

        foreach (
            $geometricMeans as $index => $gm
        ) {

            $weights[] = [
                'criteria_id' =>
                    $criteria[$index]->id,

                'weight' =>
                    round(
                        $gm / $totalGM,
                        8
                    )
            ];
        }

        return $weights;
    }

    /**
     * Simpan Bobot
     */
    public function saveWeights(
        int $groupId,
        int $positionId
    ) {
        $weights =
            $this->calculateWeights(
                $groupId,
                $positionId
            );

        foreach ($weights as $weight) {

            $this->weightRepository
                ->updateOrCreate(
                    $positionId,
                    $weight['criteria_id'],
                    $weight['weight']
                );
        }

        return true;
    }

    /**
     * Consistency Ratio
     */
    public function calculateConsistencyRatio(
        int $groupId,
        int $positionId
    ) {
        $generated =
            $this->generateMatrix(
                $groupId,
                $positionId
            );

        $matrix =
            $generated['matrix'];

        $weights =
            $this->calculateWeights(
                $groupId,
                $positionId
            );

        $size = count($matrix);

        $weightVector =
            array_column(
                $weights,
                'weight'
            );

        $weightedSum = [];

        for ($i = 0; $i < $size; $i++) {

            $sum = 0;

            for ($j = 0; $j < $size; $j++) {

                $sum +=
                    $matrix[$i][$j]
                    *
                    $weightVector[$j];
            }

            $weightedSum[$i] = $sum;
        }

        $lambda = [];

        for ($i = 0; $i < $size; $i++) {

            $lambda[] =
                $weightedSum[$i]
                /
                $weightVector[$i];
        }

        $lambdaMax =
            array_sum($lambda)
            /
            $size;

        $ci =
            ($lambdaMax - $size)
            /
            ($size - 1);

        $riTable = [
            1 => 0,
            2 => 0,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49
        ];

        $ri =
            $riTable[$size] ?? 1.49;

        $cr =
            $ri == 0
            ? 0
            : $ci / $ri;

        return [
            'lambda_max' =>
                round($lambdaMax, 6),

            'ci' =>
                round($ci, 6),

            'cr' =>
                round($cr, 6),

            'is_consistent' =>
                $cr < 0.1
        ];
    }
}