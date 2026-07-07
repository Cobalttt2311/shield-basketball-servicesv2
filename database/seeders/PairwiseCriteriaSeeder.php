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

        // -- GROUP 1 (KU 8-12) --
        $set1 = CriteriaSet::where('group_id', 1)->latest('id')->first();
        if (! $set1) {
            throw new \Exception('Criteria set for group 1 not found');
        }

        $c1 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Skill')->first()?->id;
        $c2 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Fisik')->first()?->id;
        $c3 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Antropometri')->first()?->id;
        $c4 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Pemahaman Bermain')->first()?->id;

        $guard = Position::where('group_id', 1)->where('name', 'Guard')->first()?->id;
        $forward = Position::where('group_id', 1)->where('name', 'Forward')->first()?->id;
        $center1 = Position::where('group_id', 1)->where('name', 'Center')->first()?->id;

        $positions1 = array_filter([$guard, $forward, $center1]);

        $pairwiseSet1 = PairwiseSet::where('group_id', 1)->first();
        $pairwiseSetId1 = $pairwiseSet1?->id;

        if ($pairwiseSetId1) {
            PairwiseCriteria::whereIn('position_id', $positions1)
                ->where('pairwise_set_id', $pairwiseSetId1)
                ->delete();

            // Guard: Skill > PemahamanBermain > Fisik > Antropometri
            // Forward: Skill = Fisik > Antropometri > PemahamanBermain
            // Center: Antropometri > Fisik > PemahamanBermain > Skill
            $posMapping1 = [
                $guard => [
                    [$c1, $c2, 3.0],
                    [$c1, $c3, 5.0],
                    [$c1, $c4, 2.0],
                    [$c2, $c3, 3.0],
                    [$c2, $c4, 0.3333],
                    [$c3, $c4, 0.2],
                ],
                $forward => [
                    [$c1, $c2, 1.0],
                    [$c1, $c3, 3.0],
                    [$c1, $c4, 4.0],
                    [$c2, $c3, 3.0],
                    [$c2, $c4, 4.0],
                    [$c3, $c4, 2.0],
                ],
                $center1 => [
                    [$c1, $c2, 0.3333],
                    [$c1, $c3, 0.1429],
                    [$c1, $c4, 0.5],
                    [$c2, $c3, 0.3333],
                    [$c2, $c4, 2.0],
                    [$c3, $c4, 4.0],
                ],
            ];

            $data1 = [];
            foreach ($posMapping1 as $posId => $comparisons) {
                if (! $posId) {
                    continue;
                }
                foreach ($comparisons as $comp) {
                    $data1[] = [
                        'position_id' => $posId,
                        'criteria_first_id' => $comp[0],
                        'criteria_second_id' => $comp[1],
                        'value' => $comp[2],
                        'pairwise_set_id' => $pairwiseSetId1,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            PairwiseCriteria::insert($data1);

            $pairwiseCriteriaService = app(IPairwiseCriteriaService::class);
            foreach ($positions1 as $positionId) {
                $pairwiseCriteriaService->saveWeights(1, $positionId, $pairwiseSetId1);
            }
        }

        // -- GROUP 2 (KU 13-18) --
        $set2 = CriteriaSet::where('group_id', 2)->latest('id')->first();
        if (! $set2) {
            throw new \Exception('Criteria set for group 2 not found');
        }

        $c5 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Skill')->first()?->id;
        $c6 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Fisik')->first()?->id;
        $c7 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Antropometri')->first()?->id;
        $c8 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Pemahaman Bermain')->first()?->id;

        $sg = Position::where('group_id', 2)->where('name', 'Shooting Guard')->first()?->id;
        $pg = Position::where('group_id', 2)->where('name', 'Point Guard')->first()?->id;
        $sf = Position::where('group_id', 2)->where('name', 'Small Forward')->first()?->id;
        $pf = Position::where('group_id', 2)->where('name', 'Power Forward')->first()?->id;
        $c = Position::where('group_id', 2)->where('name', 'Center')->first()?->id;

        $positions2 = array_filter([$sg, $pg, $sf, $pf, $c]);

        $pairwiseSet2 = PairwiseSet::where('group_id', 2)->first();
        $pairwiseSetId2 = $pairwiseSet2 ? $pairwiseSet2->id : null;

        PairwiseCriteria::whereIn('position_id', $positions2)
            ->where('pairwise_set_id', $pairwiseSetId2)
            ->delete();

        if (! $pairwiseSetId2) {
            return;
        }

        $data2 = [];

        $posMapping2 = [
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

        foreach ($posMapping2 as $posId => $comparisons) {
            if (! $posId) {
                continue;
            }
            foreach ($comparisons as $comp) {
                $data2[] = [
                    'position_id' => $posId,
                    'criteria_first_id' => $comp[0],
                    'criteria_second_id' => $comp[1],
                    'value' => $comp[2],
                    'pairwise_set_id' => $pairwiseSetId2,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        PairwiseCriteria::insert($data2);

        $pairwiseCriteriaService = app(IPairwiseCriteriaService::class);
        foreach ($positions2 as $positionId) {
            $pairwiseCriteriaService->saveWeights(2, $positionId, $pairwiseSetId2);
        }
    }
}
