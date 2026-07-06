<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            GroupSeeder::class,
            CoachSeeder::class,
            PlayerSeeder::class,
            PositionSeeder::class,
            CriteriaSeeder::class,
            SubCriteriaSeeder::class,
            PairwiseSetSeeder::class,
            PairwiseCriteriaSeeder::class,
            PairwiseSubCriteriaSeeder::class,
            EvaluationScoreSeeder::class,
        ]);
    }
}
