<?php

namespace Database\Seeders;

use App\Modules\Coaches\Models\PairwiseCriteria;
use App\Modules\Coaches\Models\PairwiseSet;
use App\Modules\Coaches\Services\Interfaces\IPairwiseCriteriaService;
use Illuminate\Database\Seeder;

class PairwiseCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $positions = [4, 5, 6, 7, 8];

        PairwiseCriteria::whereIn('position_id', $positions)->delete();

        PairwiseCriteria::insert([
            // Position 4
            [
                'position_id' => 4,
                'criteria_first_id' => 5,
                'criteria_second_id' => 6,
                'value' => 3.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 4,
                'criteria_first_id' => 5,
                'criteria_second_id' => 7,
                'value' => 5.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 4,
                'criteria_first_id' => 5,
                'criteria_second_id' => 8,
                'value' => 7.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 4,
                'criteria_first_id' => 6,
                'criteria_second_id' => 7,
                'value' => 2.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 4,
                'criteria_first_id' => 6,
                'criteria_second_id' => 8,
                'value' => 4.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 4,
                'criteria_first_id' => 7,
                'criteria_second_id' => 8,
                'value' => 3.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Position 5
            [
                'position_id' => 5,
                'criteria_first_id' => 5,
                'criteria_second_id' => 6,
                'value' => 0.3333,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 5,
                'criteria_first_id' => 5,
                'criteria_second_id' => 7,
                'value' => 2.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 5,
                'criteria_first_id' => 5,
                'criteria_second_id' => 8,
                'value' => 0.2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 5,
                'criteria_first_id' => 6,
                'criteria_second_id' => 7,
                'value' => 4.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 5,
                'criteria_first_id' => 6,
                'criteria_second_id' => 8,
                'value' => 0.5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 5,
                'criteria_first_id' => 7,
                'criteria_second_id' => 8,
                'value' => 0.3333,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Position 6
            [
                'position_id' => 6,
                'criteria_first_id' => 5,
                'criteria_second_id' => 6,
                'value' => 5.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 6,
                'criteria_first_id' => 5,
                'criteria_second_id' => 7,
                'value' => 0.5,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 6,
                'criteria_first_id' => 5,
                'criteria_second_id' => 8,
                'value' => 3.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 6,
                'criteria_first_id' => 6,
                'criteria_second_id' => 7,
                'value' => 0.2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 6,
                'criteria_first_id' => 6,
                'criteria_second_id' => 8,
                'value' => 2.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 6,
                'criteria_first_id' => 7,
                'criteria_second_id' => 8,
                'value' => 4.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Position 7
            [
                'position_id' => 7,
                'criteria_first_id' => 5,
                'criteria_second_id' => 6,
                'value' => 0.1429,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 7,
                'criteria_first_id' => 5,
                'criteria_second_id' => 7,
                'value' => 0.25,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 7,
                'criteria_first_id' => 5,
                'criteria_second_id' => 8,
                'value' => 2.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 7,
                'criteria_first_id' => 6,
                'criteria_second_id' => 7,
                'value' => 3.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 7,
                'criteria_first_id' => 6,
                'criteria_second_id' => 8,
                'value' => 5.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 7,
                'criteria_first_id' => 7,
                'criteria_second_id' => 8,
                'value' => 0.5,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // Position 8
            [
                'position_id' => 8,
                'criteria_first_id' => 5,
                'criteria_second_id' => 6,
                'value' => 1.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 8,
                'criteria_first_id' => 5,
                'criteria_second_id' => 7,
                'value' => 3.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 8,
                'criteria_first_id' => 5,
                'criteria_second_id' => 8,
                'value' => 0.3333,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 8,
                'criteria_first_id' => 6,
                'criteria_second_id' => 7,
                'value' => 3.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 8,
                'criteria_first_id' => 6,
                'criteria_second_id' => 8,
                'value' => 0.3333,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'position_id' => 8,
                'criteria_first_id' => 7,
                'criteria_second_id' => 8,
                'value' => 1.0,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);

        $pairwiseSet = PairwiseSet::where('group_id', 2)->first();
        $pairwiseSetId = $pairwiseSet ? $pairwiseSet->id : null;

        PairwiseCriteria::whereIn('position_id', $positions)
            ->whereNull('pairwise_set_id')
            ->update(['pairwise_set_id' => $pairwiseSetId]);

        $pairwiseCriteriaService = app(IPairwiseCriteriaService::class);
        foreach ($positions as $positionId) {
            $pairwiseCriteriaService->saveWeights(2, $positionId, $pairwiseSetId);
        }
    }
}
