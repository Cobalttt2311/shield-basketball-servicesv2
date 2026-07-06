<?php

namespace Database\Seeders;

use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\CriteriaSet;
use App\Modules\Coaches\Models\PairwiseCriteria;
use App\Modules\Coaches\Models\PairwiseSet;
use App\Modules\Coaches\Models\Position;
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

        // 1. Dapatkan kriteria ID untuk group 2 secara dinamis
        $set2 = CriteriaSet::where('group_id', 2)->latest('id')->first();
        if (! $set2) {
            throw new \Exception('Criteria set for group 2 not found');
        }

        $c5 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Skill')->first()?->id;
        $c6 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Fisik')->first()?->id;
        $c7 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Antropometri')->first()?->id;
        $c8 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Pemahaman Bermain')->first()?->id;

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
        PairwiseCriteria::whereIn('position_id', $positions)
            ->where('pairwise_set_id', $pairwiseSetId)
            ->delete();

        if (! $pairwiseSetId) {
            return;
        }

        $data = [];

        // Petakan perbandingan untuk setiap posisi
        $posMapping = [
            $sg => [
                [$c5, $c6, 3.0], [$c5, $c7, 5.0], [$c5, $c8, 7.0],
                [$c6, $c7, 2.0], [$c6, $c8, 4.0], [$c7, $c8, 3.0],
            ],
            $pg => [
                [$c5, $c6, 0.3333], [$c5, $c7, 2.0], [$c5, $c8, 0.2],
                [$c6, $c7, 4.0], [$c6, $c8, 0.5], [$c7, $c8, 0.3333],
            ],
            $sf => [
                [$c5, $c6, 5.0], [$c5, $c7, 0.5], [$c5, $c8, 3.0],
                [$c6, $c7, 0.2], [$c6, $c8, 2.0], [$c7, $c8, 4.0],
            ],
            $pf => [
                [$c5, $c6, 0.1429], [$c5, $c7, 0.25], [$c5, $c8, 2.0],
                [$c6, $c7, 3.0], [$c6, $c8, 5.0], [$c7, $c8, 0.5],
            ],
            $c => [
                [$c5, $c6, 1.0], [$c5, $c7, 3.0], [$c5, $c8, 0.3333],
                [$c6, $c7, 3.0], [$c6, $c8, 0.3333], [$c7, $c8, 1.0],
            ],
        ];

        foreach ($posMapping as $posId => $comparisons) {
            if (! $posId) {
                continue;
            }
            foreach ($comparisons as $comp) {
                $data[] = [
                    'position_id' => $posId,
                    'criteria_first_id' => $comp[0],
                    'criteria_second_id' => $comp[1],
                    'value' => $comp[2],
                    'pairwise_set_id' => $pairwiseSetId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        PairwiseCriteria::insert($data);

        $pairwiseCriteriaService = app(IPairwiseCriteriaService::class);
        foreach ($positions as $positionId) {
            $pairwiseCriteriaService->saveWeights(2, $positionId, $pairwiseSetId);
        }
    }
}
