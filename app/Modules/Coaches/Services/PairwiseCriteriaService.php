<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Admin\Models\Group;
use App\Modules\Coaches\Models\PairwiseCriteria;
use App\Modules\Coaches\Models\PairwiseSet;
use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Repositories\Interfaces\ICriteriaWeightRepository;
use App\Modules\Coaches\Repositories\Interfaces\IPairwiseCriteriaRepository;
use App\Modules\Coaches\Services\Interfaces\IAhpCalculationService;
use App\Modules\Coaches\Services\Interfaces\IPairwiseCriteriaService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;
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
        int $positionId,
        ?int $pairwiseSetId = null
    ) {
        $this->validateInputs($groupId, $positionId);

        $criteria =
            $this->pairwiseRepository
                ->getCriteriaByGroup($groupId);

        $this->pairwiseRepository
            ->deleteByPosition($positionId, $pairwiseSetId);

        $data = [];

        $count = count($criteria);

        for ($i = 0; $i < $count; $i++) {

            for ($j = $i + 1; $j < $count; $j++) {

                $data[] = [
                    'position_id' => $positionId,
                    'criteria_first_id' => $criteria[$i]->id,
                    'criteria_second_id' => $criteria[$j]->id,
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
     * Update Value
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
                            $item['criteria_first_id'],
                            $item['criteria_second_id'],
                            $item['value']
                        );
                $this->pairwiseRepository
                    ->saveValue(
                        $item['position_id'],
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
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    ) {
        $this->validateInputs($groupId, $positionId);

        $criteria =
            $this->pairwiseRepository
                ->getCriteriaByGroup($groupId);

        $matrix = [];

        $size = count($criteria);

        for ($i = 0; $i < $size; $i++) {

            for ($j = 0; $j < $size; $j++) {

                if ($i === $j) {

                    $matrix[$i][$j] = 1;
                } elseif ($i < $j) {

                    $pairwise =
                        $this->pairwiseRepository
                            ->getValue(
                                $positionId,
                                $criteria[$i]->id,
                                $criteria[$j]->id,
                                $pairwiseSetId
                            );

                    $matrix[$i][$j] =
                        $pairwise?->value ?? 0;
                } else {

                    $pairwise =
                        $this->pairwiseRepository
                            ->getValue(
                                $positionId,
                                $criteria[$j]->id,
                                $criteria[$i]->id,
                                $pairwiseSetId
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
            'matrix' => $matrix,
        ];
    }

    /**
     * Geometric Mean
     */
    public function calculateWeights(
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    ) {
        $generated =
            $this->generateMatrix(
                $groupId,
                $positionId,
                $pairwiseSetId
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

                'criteria_id' => $criteria[$index]->id,

                'weight' => $weight,
            ];
        }

        return $weights;
    }

    /**
     * Simpan Bobot
     */
    public function saveWeights(
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    ) {
        $weights =
            $this->calculateWeights(
                $groupId,
                $positionId,
                $pairwiseSetId
            );

        foreach ($weights as $weight) {
            // Update or create weight per position, criteria, and pairwise_set_id
            $this->weightRepository
                ->updateOrCreate(
                    $positionId,
                    $weight['criteria_id'],
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
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    ) {
        $generated =
            $this->generateMatrix(
                $groupId,
                $positionId,
                $pairwiseSetId
            );

        $matrix =
            $generated['matrix'];

        $weights =
            $this->calculateWeights(
                $groupId,
                $positionId,
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
        int $groupId,
        int $positionId,
        ?int $pairwiseSetId = null
    ) {
        $this->validateInputs($groupId, $positionId);

        $pairwise =
            $this->pairwiseRepository
                ->getPairwise(
                    $groupId,
                    $positionId,
                    $pairwiseSetId
                );

        return $pairwise
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'position_id' => $item->position_id,
                    'criteria_first_id' => $item->criteria_first_id,
                    'criteria_first_name' => $item->firstCriteria?->name,
                    'criteria_second_id' => $item->criteria_second_id,
                    'criteria_second_name' => $item->secondCriteria?->name,
                    'value' => $item->value,
                ];
            });
    }

    public function generatePairwiseForSet(int $pairwiseSetId): bool
    {
        $set = PairwiseSet::find($pairwiseSetId);
        if (! $set) {
            throw new \InvalidArgumentException('Pairwise set not found');
        }

        $groupId = $set->group_id;
        if (! $groupId) {
            throw new \InvalidArgumentException('Kelompok Umur (KU) belum dipilih untuk set pairwise ini.');
        }

        if ($set->criteria_set_id) {
            $criteria = \App\Modules\Coaches\Models\Criteria::where('criteria_set_id', $set->criteria_set_id)
                ->orderBy('id')
                ->get();
        } else {
            $criteria = $this->pairwiseRepository->getCriteriaByGroup($groupId);
        }
        $positions = Position::where('group_id', $groupId)->get();

        $data = [];
        $count = count($criteria);

        foreach ($positions as $position) {
            for ($i = 0; $i < $count; $i++) {
                for ($j = $i + 1; $j < $count; $j++) {
                    $exists = PairwiseCriteria::where([
                        'position_id' => $position->id,
                        'criteria_first_id' => $criteria[$i]->id,
                        'criteria_second_id' => $criteria[$j]->id,
                        'pairwise_set_id' => $pairwiseSetId,
                    ])->exists();

                    if (! $exists) {
                        $data[] = [
                            'position_id' => $position->id,
                            'criteria_first_id' => $criteria[$i]->id,
                            'criteria_second_id' => $criteria[$j]->id,
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

    public function getPairwiseForSet(int $pairwiseSetId): array
    {
        $set = PairwiseSet::find($pairwiseSetId);
        $groupId = $set?->group_id;

        $query = PairwiseCriteria::with(['firstCriteria', 'secondCriteria', 'position'])
            ->where('pairwise_set_id', $pairwiseSetId);

        if ($groupId) {
            $query->whereHas('position', function ($q) use ($groupId) {
                $q->where('group_id', $groupId);
            });
        }

        $comparisons = $query->get();

        $grouped = [];

        foreach ($comparisons as $comp) {
            $key = $comp->criteria_first_id.'-'.$comp->criteria_second_id;
            if (! isset($grouped[$key])) {
                $grouped[$key] = [
                    'criteria_first_id' => $comp->criteria_first_id,
                    'criteria_first_name' => $comp->firstCriteria?->name,
                    'criteria_second_id' => $comp->criteria_second_id,
                    'criteria_second_name' => $comp->secondCriteria?->name,
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
                $updated = PairwiseCriteria::where('id', $item['id'])
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

    public function calculateAndSaveWeightsForSet(int $pairwiseSetId): array
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
        $emptyComparisons = PairwiseCriteria::with(['firstCriteria', 'secondCriteria', 'position'])
            ->where('pairwise_set_id', $pairwiseSetId)
            ->whereNull('value')
            ->get();

        if ($emptyComparisons->isNotEmpty()) {
            $errors = [];
            foreach ($emptyComparisons as $comp) {
                $errors[] = [
                    'position_name' => $comp->position?->name,
                    'criteria_first_name' => $comp->firstCriteria?->name,
                    'criteria_second_name' => $comp->secondCriteria?->name,
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
            $this->saveWeights($groupId, $position->id, $pairwiseSetId);
            $consistencyData = $this->calculateConsistencyRatio($groupId, $position->id, $pairwiseSetId);
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

    private function validateInputs(int $groupId, int $positionId): void
    {
        $groupExists = Group::where('id', $groupId)->exists();
        if (! $groupExists) {
            throw new \InvalidArgumentException(ErrorMessages::GROUP_NOT_FOUND);
        }

        $positionExists = Position::where('id', $positionId)->exists();
        if (! $positionExists) {
            throw new \InvalidArgumentException(ErrorMessages::POSITION_NOT_FOUND);
        }
    }
}
