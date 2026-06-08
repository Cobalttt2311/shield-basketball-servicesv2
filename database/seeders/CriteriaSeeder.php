<?php

namespace Database\Seeders;

use App\Modules\Coaches\Models\Criteria;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Criteria::insert([
            [
                'group_id' => 1,
                'name' => 'Skill',
            ],
            [
                'group_id' => 1,
                'name' => 'Fisik',
            ],
            [
                'group_id' => 1,
                'name' => 'Antropometri',
            ],
            [
                'group_id' => 1,
                'name' => 'Pemahaman Bermain',
            ],

            // KU 13-18
            [
                'group_id' => 2,
                'name' => 'Skill',
            ],
            [
                'group_id' => 2,
                'name' => 'Fisik',
            ],
            [
                'group_id' => 2,
                'name' => 'Antropometri',
            ],
            [
                'group_id' => 2,
                'name' => 'Pemahaman Bermain',
            ],
        ]);
    }
}
