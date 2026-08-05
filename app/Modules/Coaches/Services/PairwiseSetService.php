<?php

namespace App\Modules\Coaches\Services;

use App\Modules\Admin\Models\Group;
use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\Evaluation;
use App\Modules\Coaches\Models\PairwiseSet;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Services\Interfaces\IPairwiseSetService;
use App\Utils\Messages\ErrorMessages\ErrorMessages;

class PairwiseSetService implements IPairwiseSetService
{
    public function getCompatibleSets(?int $evaluationId = null): array
    {
        $user = \Illuminate\Support\Facades\Auth::user();
        $coachGroupId = ($user && $user->role === 'coach' && $user->coach) ? $user->coach->group_id : null;

        if ($evaluationId === null) {
            $query = PairwiseSet::with('group');
            $sets = $query->get();
            $result = [];
            foreach ($sets as $set) {
                $isFilled = $set->pairwiseCriteria()->whereNotNull('value')->exists()
                    || $set->pairwiseSubCriteria()->whereNotNull('value')->exists();
                $result[] = [
                    'id' => $set->id,
                    'name' => $set->name,
                    'group_id' => $set->group_id,
                    'criteria_set_id' => $set->criteria_set_id,
                    'group_name' => $set->group ? $set->group->age_group : null,
                    'status' => $isFilled ? 'edit' : 'input',
                    'is_consistent' => (bool) $set->is_consistent,
                    'created_at' => $set->created_at ? $set->created_at->toDateString() : null,
                ];
            }

            return $result;
        }

        $evaluation = Evaluation::find($evaluationId);
        if (! $evaluation) {
            throw new \InvalidArgumentException(ErrorMessages::EVALUATION_NOT_FOUND);
        }

        // Always use the authenticated coach's own group to filter pairwise sets.
        // This prevents a coach of one KU from seeing sets belonging to another KU,
        // even if the evaluation_id passed in belongs to a different coach/group.
        $groupId = $coachGroupId ?? ($evaluation->coach ? $evaluation->coach->group_id : null);
        if ($groupId === null) {
            throw new \InvalidArgumentException(ErrorMessages::COACH_NOT_FOUND);
        }

        // 1. Ambil kriteria & subkriteria aktif saat ini
        $activeCriteria = Criteria::whereHas('criteriaSet', function ($q) use ($groupId) {
            $q->where('group_id', $groupId);
        })->get();
        $activeCriteriaNames = $activeCriteria->map(function ($c) {
            return strtolower(trim($c->name));
        })->sort()->values()->toArray();

        $activeCriteriaIds = $activeCriteria->pluck('id')->toArray();
        $activeSubCriteria = SubCriteria::whereIn('criteria_id', $activeCriteriaIds)->get();
        $activeSubCriteriaNames = $activeSubCriteria->map(function ($sc) {
            return strtolower(trim($sc->name));
        })->sort()->values()->toArray();

        // 2. Ambil semua pairwise sets milik group (KU) ini
        $sets = PairwiseSet::where('group_id', $groupId)->get();
        $compatibleSets = [];

        foreach ($sets as $set) {
            // Ambil kriteria unik dari set pairwise criteria
            $pairwiseCriteriaRelations = $set->pairwiseCriteria()
                ->with(['firstCriteria', 'secondCriteria'])
                ->get();

            $isNewSet = $pairwiseCriteriaRelations->count() === 0;

            if ($isNewSet) {
                // Set kosong baru selalu kompatibel
                $compatibleSets[] = [
                    'id' => $set->id,
                    'name' => $set->name,
                    'group_id' => $set->group_id,
                    'is_new' => true,
                    'is_consistent' => (bool) $set->is_consistent,
                ];

                continue;
            }

            $setCriteriaUnique = $pairwiseCriteriaRelations->flatMap(function ($pc) {
                return [$pc->firstCriteria, $pc->secondCriteria];
            })->filter()->unique('id');

            $setCriteriaNames = $setCriteriaUnique->map(function ($c) {
                return strtolower(trim($c->name));
            })->sort()->values()->toArray();

            // Ambil subkriteria unik dari set pairwise subcriteria
            $pairwiseSubCriteriaRelations = $set->pairwiseSubCriteria()
                ->with(['firstSubCriteria', 'secondSubCriteria'])
                ->get();

            $setSubCriteriaUnique = $pairwiseSubCriteriaRelations->flatMap(function ($psc) {
                return [$psc->firstSubCriteria, $psc->secondSubCriteria];
            })->filter()->unique('id');

            $setSubCriteriaNames = $setSubCriteriaUnique->map(function ($sc) {
                return strtolower(trim($sc->name));
            })->sort()->values()->toArray();

            // Cocokkan nama kriteria & subkriteria
            $criteriaMatch = ($activeCriteriaNames === $setCriteriaNames);
            $subCriteriaMatch = ($activeSubCriteriaNames === $setSubCriteriaNames);

            if ($criteriaMatch && $subCriteriaMatch) {
                $compatibleSets[] = [
                    'id' => $set->id,
                    'name' => $set->name,
                    'group_id' => $set->group_id,
                    'is_new' => false,
                    'is_consistent' => (bool) $set->is_consistent,
                ];
            }
        }

        return $compatibleSets;
    }

    public function createSet(array $data): array
    {
        $groupId = $data['group_id'] ?? null;
        if ($groupId) {
            $groupExists = Group::where('id', $groupId)->exists();
            if (! $groupExists) {
                throw new \InvalidArgumentException(ErrorMessages::GROUP_NOT_FOUND);
            }
        }

        $criteriaSetId = $data['criteria_set_id'] ?? null;
        if ($criteriaSetId) {
            $criteriaSet = \App\Modules\Coaches\Models\CriteriaSet::find($criteriaSetId);
            if (! $criteriaSet) {
                throw new \InvalidArgumentException('Criteria set not found');
            }
            if ($criteriaSet->group_id !== (int) $groupId) {
                throw new \InvalidArgumentException('Criteria set does not belong to the selected age group.');
            }
        }

        $set = PairwiseSet::create([
            'name' => $data['name'],
            'group_id' => $groupId,
            'criteria_set_id' => $criteriaSetId,
        ]);

        return [
            'id' => $set->id,
            'name' => $set->name,
            'group_id' => $set->group_id,
            'criteria_set_id' => $set->criteria_set_id,
        ];
    }

    public function updateSet(int $id, array $data): array
    {
        $set = PairwiseSet::find($id);
        if (! $set) {
            throw new \InvalidArgumentException('Pairwise set not found');
        }

        $groupId = isset($data['group_id']) ? $data['group_id'] : $set->group_id;
        if (isset($data['group_id'])) {
            $groupExists = Group::where('id', $data['group_id'])->exists();
            if (! $groupExists) {
                throw new \InvalidArgumentException(ErrorMessages::GROUP_NOT_FOUND);
            }
            $set->group_id = $data['group_id'];
        }

        if (isset($data['criteria_set_id'])) {
            $criteriaSetId = $data['criteria_set_id'];
            $criteriaSet = \App\Modules\Coaches\Models\CriteriaSet::find($criteriaSetId);
            if (! $criteriaSet) {
                throw new \InvalidArgumentException('Criteria set not found');
            }
            if ($criteriaSet->group_id !== (int) $groupId) {
                throw new \InvalidArgumentException('Criteria set does not belong to the selected age group.');
            }
            $set->criteria_set_id = $criteriaSetId;
        }

        if (isset($data['name'])) {
            $set->name = $data['name'];
        }

        $set->save();

        return [
            'id' => $set->id,
            'name' => $set->name,
            'group_id' => $set->group_id,
            'criteria_set_id' => $set->criteria_set_id,
        ];
    }

    public function getWeights(int $id): array
    {
        $set = PairwiseSet::find($id);
        if (! $set) {
            throw new \InvalidArgumentException('Pairwise set not found');
        }

        $criteriaWeights = \App\Modules\Coaches\Models\CriteriaWeight::with(['criteria', 'position'])
            ->where('pairwise_set_id', $id)
            ->get();

        $subCriteriaWeights = \App\Modules\Coaches\Models\SubCriteriaWeight::with(['subCriteria.criteria', 'position'])
            ->where('pairwise_set_id', $id)
            ->get();

        $groupId = $set->group_id;
        $positions = \App\Modules\Coaches\Models\Position::where('group_id', $groupId)->get();

        $criteriaService = app(\App\Modules\Coaches\Services\Interfaces\IPairwiseCriteriaService::class);
        $subCriteriaService = app(\App\Modules\Coaches\Services\Interfaces\IPairwiseSubCriteriaService::class);

        $riTable = [
            1 => 0.0,
            2 => 0.0,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45,
            10 => 1.49,
        ];

        $criteriaCRs = [];
        $criteriaCIs = [];
        foreach ($positions as $position) {
            try {
                $res = $criteriaService->calculateConsistencyRatio($groupId, $position->id, $id);
                $criteriaCRs[$position->id] = round($res['cr'] ?? 0.0, 4);
                $criteriaCIs[$position->id] = $res['ci'] ?? 0.0;
            } catch (\Throwable $e) {
                $criteriaCRs[$position->id] = null;
                $criteriaCIs[$position->id] = 0.0;
            }
        }

        $criterias = \App\Modules\Coaches\Models\Criteria::whereHas('criteriaSet', function ($q) use ($groupId) {
            $q->where('group_id', $groupId);
        })->get();
        $nCriteria = count($criterias);
        $riCriteria = $riTable[$nCriteria] ?? 1.49;

        $subCriteriaCRs = [];
        $subCriteriaCIs = [];
        foreach ($positions as $position) {
            $subCriteriaCRs[$position->id] = [];
            $subCriteriaCIs[$position->id] = [];
            foreach ($criterias as $criteria) {
                try {
                    $res = $subCriteriaService->calculateConsistencyRatio($position->id, $criteria->id, $id);
                    $subCriteriaCRs[$position->id][$criteria->id] = round($res['cr'] ?? 0.0, 4);
                    $subCriteriaCIs[$position->id][$criteria->id] = $res['ci'] ?? 0.0;
                } catch (\Throwable $e) {
                    $subCriteriaCRs[$position->id][$criteria->id] = null;
                    $subCriteriaCIs[$position->id][$criteria->id] = 0.0;
                }
            }
        }

        // Hitung CRH (Consistency Ratio of Hierarchy) untuk masing-masing posisi
        $crhByPosition = [];
        foreach ($positions as $position) {
            $ciCriteria = $criteriaCIs[$position->id] ?? 0.0;
            
            $weightedCISum = 0.0;
            $weightedRISum = 0.0;

            foreach ($criterias as $criteria) {
                $cw = $criteriaWeights->first(function ($item) use ($position, $criteria) {
                    return (int) $item->position_id === (int) $position->id && (int) $item->criteria_id === (int) $criteria->id;
                });
                $w_j = $cw ? (float) $cw->weight : 0.0;

                $ciSub = $subCriteriaCIs[$position->id][$criteria->id] ?? 0.0;
                $weightedCISum += $w_j * $ciSub;

                $subCount = \App\Modules\Coaches\Models\SubCriteria::where('criteria_id', $criteria->id)->count();
                $riSub = $riTable[$subCount] ?? 1.49;
                $weightedRISum += $w_j * $riSub;
            }

            $cih = $ciCriteria + $weightedCISum;
            $rih = $riCriteria + $weightedRISum;

            $crhVal = $rih > 0 ? ($cih / $rih) : 0.0;
            $crhByPosition[$position->id] = round($crhVal, 4);
        }

        return [
            'criteria_weights' => $criteriaWeights,
            'sub_criteria_weights' => $subCriteriaWeights,
            'criteria_crs' => $criteriaCRs,
            'sub_criteria_crs' => $subCriteriaCRs,
            'crh_by_position' => $crhByPosition,
        ];
    }
}
