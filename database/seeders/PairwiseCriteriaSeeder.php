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
        $pairwiseCriteriaService = app(IPairwiseCriteriaService::class);

        // ==========================================
        // 1. SEED UNTUK GROUP 1 (KU 8-12)
        // ==========================================
        $set1 = CriteriaSet::where('group_id', 1)->latest('id')->first();
        if ($set1) {
            $c1 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Skill')->first()?->id;
            $c2 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Fisik')->first()?->id;
            $c3 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Antropometri')->first()?->id;
            $c4 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Pemahaman Bermain')->first()?->id;

            $g_pos = Position::where('group_id', 1)->where('name', 'Guard')->first()?->id;
            $f_pos = Position::where('group_id', 1)->where('name', 'Forward')->first()?->id;
            $c_pos = Position::where('group_id', 1)->where('name', 'Center')->first()?->id;

            $positions1 = array_filter([$g_pos, $f_pos, $c_pos]);

            $pairwiseSet1 = PairwiseSet::where('group_id', 1)->first();
            $pairwiseSetId1 = $pairwiseSet1 ? $pairwiseSet1->id : null;

            if ($pairwiseSetId1 && $c1 && $c2 && $c3 && $c4) {
                // Hapus data lama group 1
                PairwiseCriteria::whereIn('position_id', $positions1)
                    ->where('pairwise_set_id', $pairwiseSetId1)
                    ->delete();

                $data1 = [];

                // Perbandingan:
                // - Guard: KONSISTEN
                // - Forward: TIDAK KONSISTEN (Kontradiktif ekstrem)
                // - Center: KONSISTEN
                $posMapping1 = [
                    $g_pos => [
                        [$c1, $c2, 3.0], [$c1, $c3, 5.0], [$c1, $c4, 2.0],
                        [$c2, $c3, 2.0], [$c2, $c4, 0.5], [$c3, $c4, 0.3333],
                    ],
                    $f_pos => [
                        [$c1, $c2, 9.0], [$c1, $c3, 0.1111], [$c1, $c4, 9.0],
                        [$c2, $c3, 9.0], [$c2, $c4, 0.1111], [$c3, $c4, 9.0],
                    ],
                    $c_pos => [
                        [$c1, $c2, 0.5], [$c1, $c3, 0.2], [$c1, $c4, 0.3333],
                        [$c2, $c3, 0.3333], [$c2, $c4, 0.5], [$c3, $c4, 2.0],
                    ],
                ];

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

                foreach ($positions1 as $positionId) {
                    try {
                        $pairwiseCriteriaService->saveWeights(1, $positionId, $pairwiseSetId1);
                    } catch (\Throwable $e) {
                        // Abaikan error konsistensi jika ada agar seeder tidak terhenti
                        logger()->warning("Seeder warn: " . $e->getMessage());
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

            $sg = Position::where('group_id', 2)->where('name', 'Shooting Guard')->first()?->id;
            $pg = Position::where('group_id', 2)->where('name', 'Point Guard')->first()?->id;
            $sf = Position::where('group_id', 2)->where('name', 'Small Forward')->first()?->id;
            $pf = Position::where('group_id', 2)->where('name', 'Power Forward')->first()?->id;
            $c = Position::where('group_id', 2)->where('name', 'Center')->first()?->id;

            $positions2 = array_filter([$sg, $pg, $sf, $pf, $c]);

            $pairwiseSet2 = PairwiseSet::where('group_id', 2)->first();
            $pairwiseSetId2 = $pairwiseSet2 ? $pairwiseSet2->id : null;

            if ($pairwiseSetId2 && $c5 && $c6 && $c7 && $c8) {
                // Hapus data lama group 2
                PairwiseCriteria::whereIn('position_id', $positions2)
                    ->where('pairwise_set_id', $pairwiseSetId2)
                    ->delete();

                $data2 = [];

                // Perbandingan:
                // - Shooting Guard: KONSISTEN
                // - Point Guard: TIDAK KONSISTEN (Kontradiktif ekstrem)
                // - Small Forward: KONSISTEN
                // - Power Forward: KONSISTEN
                // - Center: KONSISTEN
                $posMapping2 = [
                    $sg => [
                        [$c5, $c6, 3.0], [$c5, $c7, 5.0], [$c5, $c8, 7.0],
                        [$c6, $c7, 2.0], [$c6, $c8, 4.0], [$c7, $c8, 3.0],
                    ],
                    $pg => [
                        [$c5, $c6, 9.0], [$c5, $c7, 0.1111], [$c5, $c8, 9.0],
                        [$c6, $c7, 9.0], [$c6, $c8, 0.1111], [$c7, $c8, 9.0],
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

                foreach ($positions2 as $positionId) {
                    try {
                        $pairwiseCriteriaService->saveWeights(2, $positionId, $pairwiseSetId2);
                    } catch (\Throwable $e) {
                        logger()->warning("Seeder warn: " . $e->getMessage());
                    }
                }
            }
        }
    }
}
