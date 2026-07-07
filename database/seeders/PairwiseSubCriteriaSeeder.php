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

        // -- GROUP 1 (KU 8-12): 4 kriteria x 4 sub-kriteria --
        $set1 = CriteriaSet::where('group_id', 1)->latest('id')->first();
        if (! $set1) {
            throw new \Exception('Criteria set for group 1 not found');
        }

        $c1 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Skill')->first()?->id;
        $c2 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Fisik')->first()?->id;
        $c3 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Antropometri')->first()?->id;
        $c4 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Pemahaman Bermain')->first()?->id;

        if (! $c1 || ! $c2 || ! $c3 || ! $c4) {
            throw new \Exception('Group 1 criteria not found');
        }

        // Sub-criteria IDs per criteria (KU 8-12 punya 4 sub-kriteria per kriteria)
        $subMap1 = [
            $c1 => SubCriteria::where('criteria_id', $c1)->orderBy('id')->pluck('id')->toArray(), // BallHandling, Passing, Shooting, Defense
            $c2 => SubCriteria::where('criteria_id', $c2)->orderBy('id')->pluck('id')->toArray(), // Speed, Agility, Coordination, VerticalJump
            $c3 => SubCriteria::where('criteria_id', $c3)->orderBy('id')->pluck('id')->toArray(), // TinggiBadan, WingSpan, BeratBadan, StandingReach
            $c4 => SubCriteria::where('criteria_id', $c4)->orderBy('id')->pluck('id')->toArray(), // Positioning, DecisionMaking, Movement, Spacing
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

            /**
             * Nilai pairwise sub-kriteria KU 8-12
             *
             * Skill  (Guard)  : Shooting > Defense > Passing > BallHandling
             * Skill  (Forward): BallHandling > Passing > Shooting > Defense
             * Skill  (Center) : Passing > Defense > Shooting > BallHandling
             *
             * Fisik  (Guard)  : Speed > Agility > Coordination > VertJump
             * Fisik  (Forward): VertJump > Speed > Agility > Coordination
             * Fisik  (Center) : VertJump > Coordination > Speed > Agility
             *
             * Antro  (Guard)  : TB > WS > SR > BB
             * Antro  (Forward): WS > TB > SR > BB
             * Antro  (Center) : TB > WS > SR > BB
             *
             * Pem.Berm (Guard)  : DecMaking > Spacing > Positioning > Movement
             * Pem.Berm (Forward): Positioning > Movement > DecMaking > Spacing
             * Pem.Berm (Center) : Positioning > DecMaking > Movement > Spacing
             */
            $subPosMapping1 = [
                // GUARD
                $guard => [
                    // Skill: Shooting(2) > Defense(3) > Passing(1) > BallHandling(0)
                    $c1 => [
                        [0, 1, 3.0],    // BallHandling vs Passing
                        [0, 2, 0.3333], // BallHandling vs Shooting
                        [0, 3, 0.5],    // BallHandling vs Defense
                        [1, 2, 0.3333], // Passing vs Shooting
                        [1, 3, 0.5],    // Passing vs Defense
                        [2, 3, 3.0],    // Shooting vs Defense
                    ],
                    // Fisik: Speed(0) > Agility(1) > Coordination(2) > VertJump(3)
                    $c2 => [
                        [0, 1, 2.0],    // Speed vs Agility
                        [0, 2, 4.0],    // Speed vs Coordination
                        [0, 3, 5.0],    // Speed vs VertJump
                        [1, 2, 3.0],    // Agility vs Coordination
                        [1, 3, 4.0],    // Agility vs VertJump
                        [2, 3, 2.0],    // Coordination vs VertJump
                    ],
                    // Antropometri: TinggiBadan(0) > WingSpan(1) > StandingReach(3) > BeratBadan(2)
                    $c3 => [
                        [0, 1, 2.0],    // TinggiBadan vs WingSpan
                        [0, 2, 5.0],    // TinggiBadan vs BeratBadan
                        [0, 3, 3.0],    // TinggiBadan vs StandingReach
                        [1, 2, 4.0],    // WingSpan vs BeratBadan
                        [1, 3, 2.0],    // WingSpan vs StandingReach
                        [2, 3, 0.3333], // BeratBadan vs StandingReach
                    ],
                    // Pemahaman Bermain: DecMaking(1) > Spacing(3) > Positioning(0) > Movement(2)
                    $c4 => [
                        [0, 1, 0.3333], // Positioning vs DecMaking
                        [0, 2, 2.0],    // Positioning vs Movement
                        [0, 3, 0.5],    // Positioning vs Spacing
                        [1, 2, 5.0],    // DecMaking vs Movement
                        [1, 3, 3.0],    // DecMaking vs Spacing
                        [2, 3, 0.25],   // Movement vs Spacing
                    ],
                ],
                // FORWARD
                $forward => [
                    // Skill: BallHandling(0) > Passing(1) > Shooting(2) > Defense(3)
                    $c1 => [
                        [0, 1, 2.0],    // BallHandling vs Passing
                        [0, 2, 4.0],    // BallHandling vs Shooting
                        [0, 3, 5.0],    // BallHandling vs Defense
                        [1, 2, 2.0],    // Passing vs Shooting
                        [1, 3, 3.0],    // Passing vs Defense
                        [2, 3, 2.0],    // Shooting vs Defense
                    ],
                    // Fisik: VertJump(3) > Speed(0) > Agility(1) > Coordination(2)
                    $c2 => [
                        [0, 1, 2.0],    // Speed vs Agility
                        [0, 2, 3.0],    // Speed vs Coordination
                        [0, 3, 0.5],    // Speed vs VertJump
                        [1, 2, 2.0],    // Agility vs Coordination
                        [1, 3, 0.3333], // Agility vs VertJump
                        [2, 3, 0.2],    // Coordination vs VertJump
                    ],
                    // Antropometri: WingSpan(1) > TinggiBadan(0) > StandingReach(3) > BeratBadan(2)
                    $c3 => [
                        [0, 1, 0.5],    // TinggiBadan vs WingSpan
                        [0, 2, 3.0],    // TinggiBadan vs BeratBadan
                        [0, 3, 2.0],    // TinggiBadan vs StandingReach
                        [1, 2, 5.0],    // WingSpan vs BeratBadan
                        [1, 3, 3.0],    // WingSpan vs StandingReach
                        [2, 3, 0.3333], // BeratBadan vs StandingReach
                    ],
                    // Pemahaman Bermain: Positioning(0) > Movement(2) > DecMaking(1) > Spacing(3)
                    $c4 => [
                        [0, 1, 3.0],    // Positioning vs DecMaking
                        [0, 2, 2.0],    // Positioning vs Movement
                        [0, 3, 5.0],    // Positioning vs Spacing
                        [1, 2, 0.5],    // DecMaking vs Movement
                        [1, 3, 2.0],    // DecMaking vs Spacing
                        [2, 3, 4.0],    // Movement vs Spacing
                    ],
                ],
                // CENTER
                $center1 => [
                    // Skill: Passing(1) > Defense(3) > Shooting(2) > BallHandling(0)
                    $c1 => [
                        [0, 1, 0.3333], // BallHandling vs Passing
                        [0, 2, 0.5],    // BallHandling vs Shooting
                        [0, 3, 0.25],   // BallHandling vs Defense
                        [1, 2, 3.0],    // Passing vs Shooting
                        [1, 3, 2.0],    // Passing vs Defense
                        [2, 3, 0.5],    // Shooting vs Defense
                    ],
                    // Fisik: VertJump(3) > Coordination(2) > Speed(0) > Agility(1)
                    $c2 => [
                        [0, 1, 2.0],    // Speed vs Agility
                        [0, 2, 0.5],    // Speed vs Coordination
                        [0, 3, 0.25],   // Speed vs VertJump
                        [1, 2, 0.3333], // Agility vs Coordination
                        [1, 3, 0.2],    // Agility vs VertJump
                        [2, 3, 0.5],    // Coordination vs VertJump
                    ],
                    // Antropometri: TinggiBadan(0) > WingSpan(1) > StandingReach(3) > BeratBadan(2)
                    $c3 => [
                        [0, 1, 2.0],    // TinggiBadan vs WingSpan
                        [0, 2, 7.0],    // TinggiBadan vs BeratBadan
                        [0, 3, 4.0],    // TinggiBadan vs StandingReach
                        [1, 2, 5.0],    // WingSpan vs BeratBadan
                        [1, 3, 3.0],    // WingSpan vs StandingReach
                        [2, 3, 0.3333], // BeratBadan vs StandingReach
                    ],
                    // Pemahaman Bermain: Positioning(0) > DecMaking(1) > Movement(2) > Spacing(3)
                    $c4 => [
                        [0, 1, 2.0],    // Positioning vs DecMaking
                        [0, 2, 4.0],    // Positioning vs Movement
                        [0, 3, 6.0],    // Positioning vs Spacing
                        [1, 2, 3.0],    // DecMaking vs Movement
                        [1, 3, 4.0],    // DecMaking vs Spacing
                        [2, 3, 2.0],    // Movement vs Spacing
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
                    $pairwiseSubCriteriaService->saveWeights($positionId, $criteriaId, $pairwiseSetId1);
                }
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

        if (! $c5 || ! $c6 || ! $c7 || ! $c8) {
            throw new \Exception('Group 2 criteria not found');
        }

        $criteriaSubMap2 = [
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
        $pairwiseSetId2 = $pairwiseSet2 ? $pairwiseSet2->id : null;

        PairwiseSubCriteria::whereIn('position_id', $positions2)
            ->where('pairwise_set_id', $pairwiseSetId2)
            ->delete();

        if (! $pairwiseSetId2) {
            return;
        }

        $data2 = [];
        $possibleValues = [1.0, 2.0, 3.0, 5.0, 0.5, 0.3333, 0.2, 1.0, 4.0, 0.25, 6.0, 0.1667];
        $valIndex = 0;

        foreach ($positions2 as $positionId) {
            foreach ($criteriaSubMap2 as $criteriaId => $subIds) {
                $count = count($subIds);
                for ($i = 0; $i < $count; $i++) {
                    for ($j = $i + 1; $j < $count; $j++) {
                        $val = $possibleValues[$valIndex % count($possibleValues)];
                        $valIndex++;

                        $data2[] = [
                            'position_id' => $positionId,
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
        }

        PairwiseSubCriteria::insert($data2);

        $pairwiseSubCriteriaService = app(IPairwiseSubCriteriaService::class);
        $criteriaIds2 = [$c5, $c6, $c7, $c8];
        foreach ($positions2 as $positionId) {
            foreach ($criteriaIds2 as $criteriaId) {
                $pairwiseSubCriteriaService->saveWeights($positionId, $criteriaId, $pairwiseSetId2);
        $pairwiseSubCriteriaService = app(IPairwiseSubCriteriaService::class);
        $possibleValues = [1.0, 2.0, 3.0, 5.0, 0.5, 0.3333, 0.2, 1.0, 4.0, 0.25, 6.0, 0.1667];
        $valIndex = 0;

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
                $criteriaSubMap1 = [
                    $c1 => SubCriteria::where('criteria_id', $c1)->orderBy('id')->pluck('id')->toArray(),
                    $c2 => SubCriteria::where('criteria_id', $c2)->orderBy('id')->pluck('id')->toArray(),
                    $c3 => SubCriteria::where('criteria_id', $c3)->orderBy('id')->pluck('id')->toArray(),
                    $c4 => SubCriteria::where('criteria_id', $c4)->orderBy('id')->pluck('id')->toArray(),
                ];

                $g_pos = Position::where('group_id', 1)->where('name', 'Guard')->first()?->id;
                $f_pos = Position::where('group_id', 1)->where('name', 'Forward')->first()?->id;
                $c_pos = Position::where('group_id', 1)->where('name', 'Center')->first()?->id;

                $positions1 = array_filter([$g_pos, $f_pos, $c_pos]);

                $pairwiseSet1 = PairwiseSet::where('group_id', 1)->first();
                $pairwiseSetId1 = $pairwiseSet1 ? $pairwiseSet1->id : null;

                if ($pairwiseSetId1) {
                    // Hapus data lama group 1
                    PairwiseSubCriteria::whereIn('position_id', $positions1)
                        ->where('pairwise_set_id', $pairwiseSetId1)
                        ->delete();

                    $data1 = [];

                    foreach ($positions1 as $positionId) {
                        foreach ($criteriaSubMap1 as $criteriaId => $subIds) {
                            $count = count($subIds);
                            for ($i = 0; $i < $count; $i++) {
                                for ($j = $i + 1; $j < $count; $j++) {
                                    $val = $possibleValues[$valIndex % count($possibleValues)];
                                    $valIndex++;

                                    $data1[] = [
                                        'position_id' => $positionId,
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
                    }

                    PairwiseSubCriteria::insert($data1);

                    $criteriaIds1 = [$c1, $c2, $c3, $c4];
                    foreach ($positions1 as $positionId) {
                        foreach ($criteriaIds1 as $criteriaId) {
                            $pairwiseSubCriteriaService->saveWeights($positionId, $criteriaId, $pairwiseSetId1);
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
                $criteriaSubMap2 = [
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
                $pairwiseSetId2 = $pairwiseSet2 ? $pairwiseSet2->id : null;

                if ($pairwiseSetId2) {
                    // Hapus data lama group 2
                    PairwiseSubCriteria::whereIn('position_id', $positions2)
                        ->where('pairwise_set_id', $pairwiseSetId2)
                        ->delete();

                    $data2 = [];

                    foreach ($positions2 as $positionId) {
                        foreach ($criteriaSubMap2 as $criteriaId => $subIds) {
                            $count = count($subIds);
                            for ($i = 0; $i < $count; $i++) {
                                for ($j = $i + 1; $j < $count; $j++) {
                                    $val = $possibleValues[$valIndex % count($possibleValues)];
                                    $valIndex++;

                                    $data2[] = [
                                        'position_id' => $positionId,
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
                    }

                    PairwiseSubCriteria::insert($data2);

                    $criteriaIds2 = [$c5, $c6, $c7, $c8];
                    foreach ($positions2 as $positionId) {
                        foreach ($criteriaIds2 as $criteriaId) {
                            $pairwiseSubCriteriaService->saveWeights($positionId, $criteriaId, $pairwiseSetId2);
                        }
                    }
                }
            }
        }
    }
}
