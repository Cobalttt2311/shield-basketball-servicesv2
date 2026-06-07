<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Coaches\Repositories\Interfaces\IPairwiseCriteriaRepository;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaWeightRepository;
use App\Modules\Coaches\Services\Interfaces\IPairwiseCriteriaService;
use App\Modules\Coaches\Services\Interfaces\IAhpCalculationService;
use Illuminate\Support\Facades\DB;


class PairwiseCriteriaService implements IPairwiseCriteriaService
{
    protected $pairwiseRepository;
    protected $weightRepository;
    protected $ahpService;

    public function __construct(
        IPairwiseCriteriaRepository $pairwiseRepository,
        ICriteriaWeightRepository $weightRepository,
        IAhpCalculationService $ahpService
    ) {
        $this->pairwiseRepository = $pairwiseRepository;
        $this->weightRepository = $weightRepository;
        $this->ahpService = $ahpService;

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
    public function saveValue(
        array $data
    ):void 
    {
        DB::transaction(function () use ($data) {
            foreach ($data as $item) {
                $normalized =
                    $this->ahpService
                        ->normalizeReciprocal(
                            $item['criteria_first_id'],
                            $item['criteria_second_id'],
                            $item['value']
                        );
                $this->pairwiseRepository
                    ->saveValue(
                        $item['position_id'],
                        $normalized['first_id'],
                        $normalized['second_id'],
                        $normalized['value']
                    );
            }
        });
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

        $weightVector =
            $this->ahpService
                ->calculateEigenVector(
                    $matrix
                );

        $weights = [];

        foreach (
            $weightVector as $index => $weight
        ) {

            $weights[] = [

                'criteria_id' =>
                    $criteria[$index]->id,

                'weight' => $weight
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

        $weightVector =
            array_column(
                $weights,
                'weight'
            );

        return
            $this->ahpService
                ->calculateConsistency(
                    $matrix,
                    $weightVector
                );
    }
}