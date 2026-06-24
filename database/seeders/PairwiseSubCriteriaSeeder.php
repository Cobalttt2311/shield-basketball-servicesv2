<?php

namespace Database\Seeders;

use App\Modules\Coaches\Models\PairwiseSet;
use App\Modules\Coaches\Models\PairwiseSubCriteria;
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
        $positions = [4, 5, 6, 7, 8];

        PairwiseSubCriteria::whereIn('position_id', $positions)->delete();

        // Sub-criteria IDs mapped per criteria_id for KU 13-18
        $criteriaSubMap = [
            5 => [17, 18, 19, 20, 21, 22, 23], // Skill
            6 => [24, 25, 26, 27, 28],         // Fisik
            7 => [29, 30, 31, 32],             // Antropometri
            8 => [33, 34, 35, 36, 37],         // Pemahaman Bermain
        ];

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
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }
                }
            }
        }

        PairwiseSubCriteria::insert($data);

        $pairwiseSet = PairwiseSet::where('group_id', 2)->first();
        $pairwiseSetId = $pairwiseSet ? $pairwiseSet->id : null;

        PairwiseSubCriteria::whereIn('position_id', $positions)
            ->whereNull('pairwise_set_id')
            ->update(['pairwise_set_id' => $pairwiseSetId]);

        $pairwiseSubCriteriaService = app(IPairwiseSubCriteriaService::class);
        $criteriaIds = [5, 6, 7, 8];
        foreach ($positions as $positionId) {
            foreach ($criteriaIds as $criteriaId) {
                $pairwiseSubCriteriaService->saveWeights($positionId, $criteriaId, $pairwiseSetId);
            }
        }
    }
}
