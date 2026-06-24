<?php

namespace Database\Seeders;

use App\Modules\Admin\Models\Coach;
use App\Modules\Admin\Models\Player;
use App\Modules\Coaches\Models\Evaluation;
use App\Modules\Coaches\Models\EvaluationScore;
use App\Modules\Coaches\Models\PairwiseSet;
use Illuminate\Database\Seeder;

class EvaluationScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();

        $coach = Coach::where('group_id', 2)->first() ?? Coach::first();
        $coachId = $coach ? $coach->id : 1;

        $pairwiseSet = PairwiseSet::where('group_id', 2)->first();
        $pairwiseSetId = $pairwiseSet ? $pairwiseSet->id : null;

        $evaluation = Evaluation::firstOrCreate(
            ['title' => 'Evaluasi Utama'],
            [
                'date' => $now->toDateString(),
                'coach_id' => $coachId,
                'pairwise_set_id' => $pairwiseSetId,
            ]
        );

        // Hapus score lama untuk evaluation ini jika ada untuk mencegah duplikasi
        EvaluationScore::where('evaluation_id', $evaluation->id)->delete();

        $data = [];

        $playerIds = Player::where('group_id', $coach->group_id)->pluck('id')->toArray();

        foreach ($playerIds as $playerId) {
            for ($subCriteriaId = 17; $subCriteriaId <= 37; $subCriteriaId++) {
                // Generate score sesuai kriteria: range 68-95, dominan di 75-85
                $score = $this->getRandomScore();

                $data[] = [
                    'evaluation_id' => $evaluation->id,
                    'player_id' => $playerId,
                    'sub_criteria_id' => $subCriteriaId,
                    'score' => $score,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        EvaluationScore::insert($data);
    }

    /**
     * Get random score between 68 and 95, heavily centered around 75 to 85.
     */
    private function getRandomScore(): int
    {
        $rand = rand(1, 100);

        if ($rand <= 70) {
            // 70% probability: 75 to 85
            return rand(75, 85);
        }

        // 30% probability: 68 to 95
        return rand(68, 95);
    }
}
