<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Coaches\Repositories\Interfaces\IPairwiseSubCriteriaRepository;
use App\Modules\Coaches\Repositories\Interfaces\ISubCriteriaWeightRepository;
use App\Modules\Coaches\Services\Interfaces\IPairwiseSubCriteriaService;

class PairwiseSubCriteriaService
    implements IPairwiseSubCriteriaService
{
    protected $pairwiseRepository;
    protected $weightRepository;

    public function __construct(
        IPairwiseSubCriteriaRepository $pairwiseRepository,
        ISubCriteriaWeightRepository $weightRepository
    ) {
        $this->pairwiseRepository = $pairwiseRepository;
        $this->weightRepository = $weightRepository;
    }

    /**
     * Generate Pairwise Otomatis
     */
    public function generatePairwise(
        int $positionId,
        int $criteriaId
    ) {
        $subCriteria =
            $this->pairwiseRepository
                ->getSubCriteriaByCriteria(
                    $criteriaId
                );

        $this->pairwiseRepository
            ->deleteByPositionAndCriteria(
                $positionId,
                $criteriaId
            );

        $data = [];

        $count = count($subCriteria);

        for ($i = 0; $i < $count; $i++) {

            for ($j = $i + 1; $j < $count; $j++) {

                $data[] = [
                    'position_id' => $positionId,

                    'criteria_id' => $criteriaId,

                    'sub_criteria_first_id' =>
                        $subCriteria[$i]->id,

                    'sub_criteria_second_id' =>
                        $subCriteria[$j]->id,

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
     * Update Pairwise Value
     */
    public function updateValue(
        array $data
    ) {
        return $this->pairwiseRepository
            ->updateValue(
                $data['position_id'],
                $data['criteria_id'],
                $data['sub_criteria_first_id'],
                $data['sub_criteria_second_id'],
                $data['value']
            );
    }

    /**
     * Generate Matrix
     * Reciprocal Otomatis
     */
    public function generateMatrix(
        int $positionId,
        int $criteriaId
    ) {
        $subCriteria =
            $this->pairwiseRepository
                ->getSubCriteriaByCriteria(
                    $criteriaId
                );

        $matrix = [];

        $size = count($subCriteria);

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
                                $criteriaId,
                                $subCriteria[$i]->id,
                                $subCriteria[$j]->id
                            );

                    $matrix[$i][$j] =
                        $pairwise?->value ?? 0;
                }

                else {

                    $pairwise =
                        $this->pairwiseRepository
                            ->getValue(
                                $positionId,
                                $criteriaId,
                                $subCriteria[$j]->id,
                                $subCriteria[$i]->id
                            );

                    $matrix[$i][$j] =
                        $pairwise &&
                        $pairwise->value
                        ? round(
                            1 / $pairwise->value,
                            8
                        )
                        : 0;
                }
            }
        }

        return [
            'sub_criteria' => $subCriteria,
            'matrix' => $matrix
        ];
    }

    /**
     * Geometric Mean
     */
    public function calculateWeights(
        int $positionId,
        int $criteriaId
    ) {
        $generated =
            $this->generateMatrix(
                $positionId,
                $criteriaId
            );

        $matrix =
            $generated['matrix'];

        $subCriteria =
            $generated['sub_criteria'];

        $size = count($matrix);

        $geometricMeans = [];

        for ($i = 0; $i < $size; $i++) {

            $product = 1;

            for ($j = 0; $j < $size; $j++) {

                $product *=
                    $matrix[$i][$j];
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

                'sub_criteria_id' =>
                    $subCriteria[$index]->id,

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
     * Simpan Weight
     */
    public function saveWeights(
        int $positionId,
        int $criteriaId
    ) {
        $weights =
            $this->calculateWeights(
                $positionId,
                $criteriaId
            );

        foreach ($weights as $weight) {

            $this->weightRepository
                ->updateOrCreate(
                    $positionId,
                    $weight['sub_criteria_id'],
                    $weight['weight']
                );
        }

        return true;
    }

    /**
     * Consistency Ratio
     */
    public function calculateConsistencyRatio(
        int $positionId,
        int $criteriaId
    ) {
        $generated =
            $this->generateMatrix(
                $positionId,
                $criteriaId
            );

        $matrix =
            $generated['matrix'];

        $weights =
            $this->calculateWeights(
                $positionId,
                $criteriaId
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