<?php

namespace Database\Seeders;

use App\Modules\Coaches\Models\PairwiseSet;
use Illuminate\Database\Seeder;

class PairwiseSetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PairwiseSet::firstOrCreate(
            ['name' => 'Default Pairwise Set KU 8-12'],
            ['group_id' => 1]
        );

        PairwiseSet::firstOrCreate(
            ['name' => 'Default Pairwise Set KU 13-18'],
            ['group_id' => 2]
        );
    }
}
