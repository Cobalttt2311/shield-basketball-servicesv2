<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\PairwiseSet;
use App\Modules\Coaches\Models\PairwiseSubCriteria;
use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Models\SubCriteria;
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

    public function generatePairwiseForSet(int $pairwiseSetId, int $criteriaId): bool
    {
        $set = PairwiseSet::find($pairwiseSetId);
        if (! $set) {
            throw new \InvalidArgumentException('Pairwise set not found');
        }

        $groupId = $set->group_id;
        if (! $groupId) {
            throw new \InvalidArgumentException('Kelompok Umur (KU) belum dipilih untuk set pairwise ini.');
        }

        $subCriteria = SubCriteria::where('criteria_id', $criteriaId)->orderBy('id')->get();
        $positions = Position::where('group_id', $groupId)->get();

        $data = [];
        $count = count($subCriteria);

        foreach ($positions as $position) {
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $exists = PairwiseSubCriteria::where([
                        'position_id' => $position->id,
                        'criteria_id' => $criteriaId,
                        'sub_criteria_first_id' => $subCriteria[$i]->id,
                        'sub_criteria_second_id' => $subCriteria[$j]->id,
                        'pairwise_set_id' => $pairwiseSetId,
                    ])->exists();

                    if (! $exists) {
                        $data[] = [
                            'position_id' => $position->id,
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
            }
        }

        if (! empty($data)) {
            $this->pairwiseRepository->insertMany($data);
        }

        return true;
    }

    public function getPairwiseForSet(int $pairwiseSetId, int $criteriaId): array
    {
        $set = PairwiseSet::find($pairwiseSetId);
        $groupId = $set?->group_id;

        $query = PairwiseSubCriteria::with(['firstSubCriteria', 'secondSubCriteria', 'position'])
            ->where('pairwise_set_id', $pairwiseSetId)
            ->where('criteria_id', $criteriaId);

        if ($groupId) {
            $query->whereHas('position', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            });
        }

        $comparisons = $query->get();

        $grouped = [];

        foreach ($comparisons as $comp) {
            $key = $comp->sub_criteria_first_id.'-'.$comp->sub_criteria_second_id;
            if (! isset($grouped[$key])) {
                $grouped[$key] = [
                    'sub_criteria_first_id' => $comp->sub_criteria_first_id,
                    'sub_criteria_first_name' => $comp->firstSubCriteria?->name,
                    'sub_criteria_second_id' => $comp->sub_criteria_second_id,
                    'sub_criteria_second_name' => $comp->secondSubCriteria?->name,
                    'comparisons' => [],
                ];
            }
            $grouped[$key]['comparisons'][] = [
                'id' => $comp->id,
                'position_id' => $comp->position_id,
                'position_name' => $comp->position?->name,
                'value' => $comp->value !== null ? (float) $comp->value : null,
            ];
        }

        return array_values($grouped);
    }

    public function saveValueForSet(int $pairwiseSetId, array $comparisons): void
    {
        DB::transaction(function () use ($pairwiseSetId, $comparisons) {
            foreach ($comparisons as $item) {
                $updated = PairwiseSubCriteria::where('id', $item['id'])
                    ->where('pairwise_set_id', $pairwiseSetId)
                    ->update([
                        'value' => $item['value'],
                    ]);

                if ($updated === 0) {
                    throw new \InvalidArgumentException("Perbandingan dengan ID {$item['id']} tidak ditemukan pada set pairwise ID {$pairwiseSetId}.");
                }
            }
        });
    }

    public function calculateAndSaveWeightsForSet(int $pairwiseSetId, int $criteriaId): array
    {
        $set = PairwiseSet::find($pairwiseSetId);
        if (! $set) {
            throw new \InvalidArgumentException('Pairwise set not found');
        }

        $groupId = $set->group_id;
        if (! $groupId) {
            throw new \InvalidArgumentException('Kelompok Umur (KU) belum diset untuk set pairwise ini.');
        }

        // 1. Cek kelengkapan data (apakah ada value yang null)
        $emptyComparisons = PairwiseSubCriteria::with(['firstSubCriteria', 'secondSubCriteria', 'position'])
            ->where('pairwise_set_id', $pairwiseSetId)
            ->where('criteria_id', $criteriaId)
            ->whereNull('value')
            ->get();

        if ($emptyComparisons->isNotEmpty()) {
            $errors = [];
            foreach ($emptyComparisons as $comp) {
                $errors[] = [
                    'position_name' => $comp->position?->name,
                    'sub_criteria_first_name' => $comp->firstSubCriteria?->name,
                    'sub_criteria_second_name' => $comp->secondSubCriteria?->name,
                ];
            }

            return [
                'success' => false,
                'errors' => $errors,
            ];
        }

        // 2. Lakukan kalkulasi untuk setiap posisi
        $positions = Position::where('group_id', $groupId)->get();
        $results = [];

        foreach ($positions as $position) {
            $this->saveWeights($position->id, $criteriaId, $pairwiseSetId);
            $consistencyData = $this->calculateConsistencyRatio($position->id, $criteriaId, $pairwiseSetId);
            $crVal = $consistencyData['cr'] ?? 0.0;
            $results[] = [
                'position_id' => $position->id,
                'position_name' => $position->name,
                'is_consistent' => $crVal < 0.1,
                'consistency_ratio' => round($crVal, 4),
            ];
        }

        return [
            'success' => true,
            'results' => $results,
        ];
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
