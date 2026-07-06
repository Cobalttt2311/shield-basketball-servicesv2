<?php

namespace Database\Seeders;

use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\CriteriaSet;
use App\Modules\Coaches\Models\PairwiseSet;
use App\Modules\Coaches\Models\PairwiseSubCriteria;
use App\Modules\Coaches\Models\Position;
use App\Modules\Coaches\Models\SubCriteria;
use App\Modules\Coaches\Services\Interfaces\IPairwiseSubCriteriaService;
use Illuminate\Database\Seeder;

class PairwiseSubCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        // 1. Dapatkan kriteria ID untuk group 2 secara dinamis
        $set2 = CriteriaSet::where('group_id', 2)->latest('id')->first();
        if (! $set2) {
            throw new \Exception('Criteria set for group 2 not found');
        }

        $c5 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Skill')->first()?->id;
        $c6 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Fisik')->first()?->id;
        $c7 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Antropometri')->first()?->id;
        $c8 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Pemahaman Bermain')->first()?->id;

        if (! $c5 || ! $c6 || ! $c7 || ! $c8) {
            throw new \Exception('Group 2 criteria not found');
        }

        // Sub-criteria IDs mapped per criteria_id
        $criteriaSubMap = [
            $c5 => SubCriteria::where('criteria_id', $c5)->orderBy('id')->pluck('id')->toArray(),
            $c6 => SubCriteria::where('criteria_id', $c6)->orderBy('id')->pluck('id')->toArray(),
            $c7 => SubCriteria::where('criteria_id', $c7)->orderBy('id')->pluck('id')->toArray(),
            $c8 => SubCriteria::where('criteria_id', $c8)->orderBy('id')->pluck('id')->toArray(),
        ];

        // 2. Dapatkan posisi ID untuk group 2 secara dinamis
        $sg = Position::where('group_id', 2)->where('name', 'Shooting Guard')->first()?->id;
        $pg = Position::where('group_id', 2)->where('name', 'Point Guard')->first()?->id;
        $sf = Position::where('group_id', 2)->where('name', 'Small Forward')->first()?->id;
        $pf = Position::where('group_id', 2)->where('name', 'Power Forward')->first()?->id;
        $c = Position::where('group_id', 2)->where('name', 'Center')->first()?->id;

        $positions = array_filter([$sg, $pg, $sf, $pf, $c]);

        // 3. Dapatkan pairwise_set_id
        $pairwiseSet = PairwiseSet::where('group_id', 2)->first();
        $pairwiseSetId = $pairwiseSet ? $pairwiseSet->id : null;

        // Bersihkan data lama
        PairwiseSubCriteria::whereIn('position_id', $positions)
            ->where('pairwise_set_id', $pairwiseSetId)
            ->delete();

        if (! $pairwiseSetId) {
            return;
        }

        $data = [];
        $possibleValues = [1.0, 2.0, 3.0, 5.0, 0.5, 0.3333, 0.2, 1.0, 4.0, 0.25, 6.0, 0.1667];
        $valIndex = 0;

        foreach ($positions as $positionId) {
            foreach ($criteriaSubMap as $criteriaId => $subIds) {
                $count = count($subIds);
                for ($i = 0; $i < $count; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        $val = $possibleValues[$valIndex % count($possibleValues)];
                        $valIndex++;

                        $data[] = [
                            'position_id' => $positionId,
                            'criteria_id' => $criteriaId,
                            'sub_criteria_first_id' => $subIds[$i],
                            'sub_criteria_second_id' => $subIds[$j],
                            'value' => $val,
                            'pairwise_set_id' => $pairwiseSetId,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }
        }

        PairwiseSubCriteria::insert($data);

        $pairwiseSubCriteriaService = app(IPairwiseSubCriteriaService::class);
        $criteriaIds = [$c5, $c6, $c7, $c8];
        foreach ($positions as $positionId) {
            foreach ($criteriaIds as $criteriaId) {
                $pairwiseSubCriteriaService->saveWeights($positionId, $criteriaId, $pairwiseSetId);
            }
        }
    }
}
