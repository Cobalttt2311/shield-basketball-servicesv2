<?php

namespace Database\Seeders;

use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\CriteriaSet;
use Illuminate\Database\Seeder;

class CriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $set1 = CriteriaSet::create([
            'name' => 'Set Kriteria Default KU 10-12',
            'group_id' => 1,
        ]);

        $set2 = CriteriaSet::create([
            'name' => 'Set Kriteria Default KU 13-18',
            'group_id' => 2,
        ]);

        Criteria::insert([
            [
                'criteria_set_id' => $set1->id,
                'name' => 'Skill',
            ],
            [
                'criteria_set_id' => $set1->id,
                'name' => 'Fisik',
            ],
            [
                'criteria_set_id' => $set1->id,
                'name' => 'Antropometri',
            ],
            [
                'criteria_set_id' => $set1->id,
                'name' => 'Pemahaman Bermain',
            ],

            // KU 13-18
            [
                'criteria_set_id' => $set2->id,
                'name' => 'Skill',
            ],
            [
                'criteria_set_id' => $set2->id,
                'name' => 'Fisik',
            ],
            [
                'criteria_set_id' => $set2->id,
                'name' => 'Antropometri',
            ],
            [
                'criteria_set_id' => $set2->id,
                'name' => 'Pemahaman Bermain',
            ],
        ]);
    }
}
