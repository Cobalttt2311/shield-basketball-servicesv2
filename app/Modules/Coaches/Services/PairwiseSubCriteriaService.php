<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Repositories\Interfaces\IPairwiseSubCriteriaRepository;
use App\Modules\Coaches\Repositories\Interfaces\ISubCriteriaWeightRepository;
use App\Modules\Coaches\Services\Interfaces\IAhpCalculationService;
use App\Modules\Coaches\Services\Interfaces\IPairwiseSubCriteriaService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
use Illuminate\Support\Facades\DB;

class PairwiseSubCriteriaService implements IPairwiseSubCriteriaService
{
    protected $pairwiseRepository;

    protected $weightRepository;

    protected $ahpService;

    public function __construct(
        IPairwiseSubCriteriaRepository $pairwiseRepository,
        ISubCriteriaWeightRepository $weightRepository,
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
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    ) {
        $this->validateInputs($positionId, $criteriaId);

        $subCriteria =
            $this->pairwiseRepository
                ->getSubCriteriaByCriteria(
                    $criteriaId
                );

        $this->pairwiseRepository
            ->deleteByPositionAndCriteria(
                $positionId,
                $criteriaId,
                $pairwiseSetId
            );

        $data = [];

        $count = count($subCriteria);

        for ($i = 0; $i < $count; $i++) {

            for ($j = $i + 1; $j < $count; $j++) {

                $data[] = [
                    'position_id' => $positionId,
                    'criteria_id' => $criteriaId,
                    'sub_criteria_first_id' => $subCriteria[$i]->id,
                    'sub_criteria_second_id' => $subCriteria[$j]->id,
                    'value' => null,
                    'pairwise_set_id' => $pairwiseSetId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (! empty($data)) {

            $this->pairwiseRepository
                ->insertMany($data);
        }

        return true;
    }

    /**
     * Update Pairwise Value
     */
    public function saveValue(
        array $data,
        ?int $pairwiseSetId = null
    ): void {
        DB::transaction(function () use ($data, $pairwiseSetId) {
            foreach ($data as $item) {
                $normalized =
                    $this->ahpService
                        ->normalizeReciprocal(
                            $item['sub_criteria_first_id'],
                            $item['sub_criteria_second_id'],
                            $item['value']
                        );
                $this->pairwiseRepository
                    ->saveValue(
                        $item['position_id'],
                        $item['criteria_id'],
                        $normalized['first_id'],
                        $normalized['second_id'],
                        $normalized['value'],
                        $pairwiseSetId
                    );
            }
        });
    }

    /**
     * Generate Matrix
     * Reciprocal Otomatis
     */
    public function generateMatrix(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    ) {
        $this->validateInputs($positionId, $criteriaId);

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
                } elseif ($i < $j) {

                    $pairwise =
                        $this->pairwiseRepository
                            ->getValue(
                                $positionId,
                                $criteriaId,
                                $subCriteria[$i]->id,
                                $subCriteria[$j]->id,
                                $pairwiseSetId
                            );

                    $matrix[$i][$j] =
                        $pairwise?->value ?? 0;
                } else {

                    $pairwise =
                        $this->pairwiseRepository
                            ->getValue(
                                $positionId,
                                $criteriaId,
                                $subCriteria[$j]->id,
                                $subCriteria[$i]->id,
                                $pairwiseSetId
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
            'matrix' => $matrix,
        ];
    }

    /**
     * Geometric Mean
     */
    public function calculateWeights(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    ) {
        $generated =
            $this->generateMatrix(
                $positionId,
                $criteriaId,
                $pairwiseSetId
            );

        $matrix =
            $generated['matrix'];

        $subCriteria =
            $generated['sub_criteria'];

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

                'sub_criteria_id' => $subCriteria[$index]->id,

                'weight' => $weight,
            ];
        }

        return $weights;
    }

    /**
     * Simpan Weight
     */
    public function saveWeights(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    ) {
        $weights =
            $this->calculateWeights(
                $positionId,
                $criteriaId,
                $pairwiseSetId
            );

        foreach ($weights as $weight) {
            // Update or create weight per position, subcriteria, and pairwise_set_id
            $this->weightRepository
                ->updateOrCreate(
                    $positionId,
                    $weight['sub_criteria_id'],
                    $weight['weight'],
                    $pairwiseSetId
                );
        }

        return true;
    }

    /**
     * Consistency Ratio
     */
    public function calculateConsistencyRatio(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    ) {
        $generated =
            $this->generateMatrix(
                $positionId,
                $criteriaId,
                $pairwiseSetId
            );

        $matrix =
            $generated['matrix'];

        $weights =
            $this->calculateWeights(
                $positionId,
                $criteriaId,
                $pairwiseSetId
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

    public function getPairwise(
        int $positionId,
        int $criteriaId,
        ?int $pairwiseSetId = null
    ) {
        $this->validateInputs($positionId, $criteriaId);

        $pairwise =
            $this->pairwiseRepository
                ->getPairwise(
                    $positionId,
                    $criteriaId,
                    $pairwiseSetId
                );

        return $pairwise
            ->map(function ($item) {

                return [

                    'id' => $item->id,

                    'position_id' => $item->position_id,

                    'criteria_id' => $item->criteria_id,

                    'sub_criteria_first_id' => $item->sub_criteria_first_id,

                    'sub_criteria_first_name' => $item->firstSubCriteria?->name,

                    'sub_criteria_second_id' => $item->sub_criteria_second_id,

                    'sub_criteria_second_name' => $item->secondSubCriteria?->name,

                    'value' => $item->value,
                ];
            });
    }

    private function validateInputs(int $positionId, int $criteriaId): void
    {
        $positionExists = Position::where('id', $positionId)->exists();
        if (! $positionExists) {
            throw new \InvalidArgumentException(ErrorMessages::POSITION_NOT_FOUND);
        }

        $criteriaExists = Criteria::where('id', $criteriaId)->exists();
        if (! $criteriaExists) {
            throw new \InvalidArgumentException(ErrorMessages::CRITERIA_NOT_FOUND);
        }
    }
}
