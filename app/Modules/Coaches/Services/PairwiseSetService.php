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
    public function getCompatibleSets(int $evaluationId): array
    {
        $evaluation = Evaluation::find($evaluationId);
        if (! $evaluation) {
            throw new \InvalidArgumentException(ErrorMessages::EVALUATION_NOT_FOUND);
        }

        $coach = $evaluation->coach;
        if (! $coach) {
            throw new \InvalidArgumentException(ErrorMessages::COACH_NOT_FOUND);
        }

        $groupId = $coach->group_id;

        // 1. Ambil kriteria & subkriteria aktif saat ini
        $activeCriteria = Criteria::where('group_id', $groupId)->get();
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
                ];
            }
        }

        return $compatibleSets;
    }

    public function createSet(array $data): array
    {
        $groupExists = Group::where('id', $data['group_id'])->exists();
        if (! $groupExists) {
            throw new \InvalidArgumentException(ErrorMessages::GROUP_NOT_FOUND);
        }

        $set = PairwiseSet::create([
            'name' => $data['name'],
            'group_id' => $data['group_id'],
        ]);

        return [
            'id' => $set->id,
            'name' => $set->name,
            'group_id' => $set->group_id,
        ];
    }
}
