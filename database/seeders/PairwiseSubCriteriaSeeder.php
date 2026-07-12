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

        // ==========================================
        // 1. SEED UNTUK GROUP 1 (KU 8-12)
        // ==========================================
        $set1 = CriteriaSet::where('group_id', 1)->latest('id')->first();
        if ($set1) {
            $c1 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Skill')->first()?->id;
            $c2 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Fisik')->first()?->id;
            $c3 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Antropometri')->first()?->id;
            $c4 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Pemahaman Bermain')->first()?->id;

            if ($c1 && $c2 && $c3 && $c4) {
                $subMap1 = [
                    $c1 => SubCriteria::where('criteria_id', $c1)->orderBy('id')->pluck('id')->toArray(),
                    $c2 => SubCriteria::where('criteria_id', $c2)->orderBy('id')->pluck('id')->toArray(),
                    $c3 => SubCriteria::where('criteria_id', $c3)->orderBy('id')->pluck('id')->toArray(),
                    $c4 => SubCriteria::where('criteria_id', $c4)->orderBy('id')->pluck('id')->toArray(),
                ];

                $guard = Position::where('group_id', 1)->where('name', 'Guard')->first()?->id;
                $forward = Position::where('group_id', 1)->where('name', 'Forward')->first()?->id;
                $center1 = Position::where('group_id', 1)->where('name', 'Center')->first()?->id;
                $positions1 = array_filter([$guard, $forward, $center1]);

                $pairwiseSet1 = PairwiseSet::where('group_id', 1)->first();
                $pairwiseSetId1 = $pairwiseSet1?->id;

                if ($pairwiseSetId1) {
                    PairwiseSubCriteria::whereIn('position_id', $positions1)
                        ->where('pairwise_set_id', $pairwiseSetId1)
                        ->delete();

                    $subPosMapping1 = [
                        $guard => [
                            $c1 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 2.0],
                                [1, 2, 3.0],
                                [1, 3, 2.0],
                                [2, 3, 2.0],
                            ],
                            $c2 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c3 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c4 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                        ],
                        $forward => [
                            $c1 => [
                                [0, 1, 1.0],
                                [0, 2, 0.25],
                                [0, 3, 0.3333],
                                [1, 2, 0.25],
                                [1, 3, 0.3333],
                                [2, 3, 4.0],
                            ],
                            $c2 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c3 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c4 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                        ],
                        $center1 => [
                            $c1 => [
                                [0, 1, 0.3333],
                                [0, 2, 0.3333],
                                [0, 3, 0.25],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 0.3333],
                            ],
                            $c2 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c3 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c4 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                        ],
                    ];

                    $data1 = [];
                    foreach ($subPosMapping1 as $posId => $criteriaMap) {
                        if (! $posId) {
                            continue;
                        }
                        foreach ($criteriaMap as $criteriaId => $indexedComparisons) {
                            $subIds = $subMap1[$criteriaId] ?? [];
                            foreach ($indexedComparisons as $comp) {
                                [$i, $j, $val] = $comp;
                                if (! isset($subIds[$i], $subIds[$j])) {
                                    continue;
                                }
                                $data1[] = [
                                    'position_id' => $posId,
                                    'criteria_id' => $criteriaId,
                                    'sub_criteria_first_id' => $subIds[$i],
                                    'sub_criteria_second_id' => $subIds[$j],
                                    'value' => $val,
                                    'pairwise_set_id' => $pairwiseSetId1,
                                    'created_at' => $now,
                                    'updated_at' => $now,
                                ];
                            }
                        }
                    }

                    PairwiseSubCriteria::insert($data1);

                    $pairwiseSubCriteriaService = app(IPairwiseSubCriteriaService::class);
                    $criteriaIds1 = [$c1, $c2, $c3, $c4];
                    foreach ($positions1 as $positionId) {
                        foreach ($criteriaIds1 as $criteriaId) {
                            try {
                                $pairwiseSubCriteriaService->saveWeights($positionId, $criteriaId, $pairwiseSetId1);
                            } catch (\Throwable $e) {
                                logger()->warning('Seeder KU 8-12 subcriteria warn: '.$e->getMessage());
                            }
                        }
                    }
                }
            }
        }

        // ==========================================
        // 2. SEED UNTUK GROUP 2 (KU 13-18)
        // ==========================================
        $set2 = CriteriaSet::where('group_id', 2)->latest('id')->first();
        if ($set2) {
            $c5 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Skill')->first()?->id;
            $c6 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Fisik')->first()?->id;
            $c7 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Antropometri')->first()?->id;
            $c8 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Pemahaman Bermain')->first()?->id;

            if ($c5 && $c6 && $c7 && $c8) {
                $subMap2 = [
                    $c5 => SubCriteria::where('criteria_id', $c5)->orderBy('id')->pluck('id')->toArray(),
                    $c6 => SubCriteria::where('criteria_id', $c6)->orderBy('id')->pluck('id')->toArray(),
                    $c7 => SubCriteria::where('criteria_id', $c7)->orderBy('id')->pluck('id')->toArray(),
                    $c8 => SubCriteria::where('criteria_id', $c8)->orderBy('id')->pluck('id')->toArray(),
                ];

                $sg = Position::where('group_id', 2)->where('name', 'Shooting Guard')->first()?->id;
                $pg = Position::where('group_id', 2)->where('name', 'Point Guard')->first()?->id;
                $sf = Position::where('group_id', 2)->where('name', 'Small Forward')->first()?->id;
                $pf = Position::where('group_id', 2)->where('name', 'Power Forward')->first()?->id;
                $c = Position::where('group_id', 2)->where('name', 'Center')->first()?->id;

                $positions2 = array_filter([$sg, $pg, $sf, $pf, $c]);

                $pairwiseSet2 = PairwiseSet::where('group_id', 2)->first();
                $pairwiseSetId2 = $pairwiseSet2?->id;

                if ($pairwiseSetId2) {
                    PairwiseSubCriteria::whereIn('position_id', $positions2)
                        ->where('pairwise_set_id', $pairwiseSetId2)
                        ->delete();

                    $subPosMapping2 = [
                        $sg => [
                            $c5 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c6 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c7 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c8 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                        ],
                        $pg => [
                            $c5 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c6 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c7 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c8 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                        ],
                        $sf => [
                            $c5 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c6 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c7 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c8 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                        ],
                        $pf => [
                            $c5 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c6 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c7 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c8 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                        ],
                        $c => [
                            $c5 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c6 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c7 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                            $c8 => [
                                [0, 1, 1.0],
                                [0, 2, 1.0],
                                [0, 3, 1.0],
                                [1, 2, 1.0],
                                [1, 3, 1.0],
                                [2, 3, 1.0],
                            ],
                        ],
                    ];

                    $data2 = [];
                    foreach ($subPosMapping2 as $posId => $criteriaMap) {
                        if (! $posId) {
                            continue;
                        }
                        foreach ($criteriaMap as $criteriaId => $indexedComparisons) {
                            $subIds = $subMap2[$criteriaId] ?? [];
                            foreach ($indexedComparisons as $comp) {
                                [$i, $j, $val] = $comp;
                                if (! isset($subIds[$i], $subIds[$j])) {
                                    continue;
                                }
                                $data2[] = [
                                    'position_id' => $posId,
                                    'criteria_id' => $criteriaId,
                                    'sub_criteria_first_id' => $subIds[$i],
                                    'sub_criteria_second_id' => $subIds[$j],
                                    'value' => $val,
                                    'pairwise_set_id' => $pairwiseSetId2,
                                    'created_at' => $now,
                                    'updated_at' => $now,
                                ];
                            }
                        }
                    }

                    PairwiseSubCriteria::insert($data2);

                    $pairwiseSubCriteriaService = app(IPairwiseSubCriteriaService::class);
                    $criteriaIds2 = [$c5, $c6, $c7, $c8];
                    foreach ($positions2 as $positionId) {
                        foreach ($criteriaIds2 as $criteriaId) {
                            try {
                                $pairwiseSubCriteriaService->saveWeights($positionId, $criteriaId, $pairwiseSetId2);
                            } catch (\Throwable $e) {
                                logger()->warning('Seeder KU 13-18 subcriteria warn: '.$e->getMessage());
                            }
                        }
                    }
                }
            }
        }
    }
}
