<?php

namespace Database\Seeders;

use App\Modules\Coaches\Models\SubCriteria;
use Illuminate\Database\Seeder;

class SubCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SubCriteria::insert([
            // KU 8-12
            // Skill
            [
                'criteria_id' => 1,
                'name' => 'Ball Handling',
            ],
            [
                'criteria_id' => 1,
                'name' => 'Passing',
            ],
            [
                'criteria_id' => 1,
                'name' => 'Shooting',
            ],
            [
                'criteria_id' => 1,
                'name' => 'Defense',
            ],

            // Fisikk
            [
                'criteria_id' => 2,
                'name' => 'Speed',
            ],
            [
                'criteria_id' => 2,
                'name' => 'Agility',
            ],
            [
                'criteria_id' => 2,
                'name' => 'Coordination',
            ],
            [
                'criteria_id' => 2,
                'name' => 'Vertical Jump',
            ],

            // Antropometri
            [
                'criteria_id' => 3,
                'name' => 'Tinggi Badan',
            ],
            [
                'criteria_id' => 3,
                'name' => 'Wing Span',
            ],
            [
                'criteria_id' => 3,
                'name' => 'Berat Badan',
            ],
            [
                'criteria_id' => 3,
                'name' => 'Standing Reach',
            ],

            // Pemahaman Bermain
            [
                'criteria_id' => 4,
                'name' => 'Positioning',
            ],
            [
                'criteria_id' => 4,
                'name' => 'Decision Making',
            ],
            [
                'criteria_id' => 4,
                'name' => 'Movement',
            ],
            [
                'criteria_id' => 4,
                'name' => 'Spacing',
            ],

            // KU 13-18
            // Skill
            [
                'criteria_id' => 5,
                'name' => 'Ball Handling',
            ],
            [
                'criteria_id' => 5,
                'name' => 'Passing',
            ],
            [
                'criteria_id' => 5,
                'name' => 'Shooting',
            ],
            [
                'criteria_id' => 5,
                'name' => 'Defense',
            ],
            [
                'criteria_id' => 5,
                'name' => 'Rebound',
            ],
            [
                'criteria_id' => 5,
                'name' => 'Post Move',
            ],
            [
                'criteria_id' => 5,
                'name' => 'Positioning',
            ],

            // Fisikk
            [
                'criteria_id' => 6,
                'name' => 'Speed',
            ],
            [
                'criteria_id' => 6,
                'name' => 'Agility',
            ],
            [
                'criteria_id' => 6,
                'name' => 'Vertical Jump',
            ],
            [
                'criteria_id' => 6,
                'name' => 'Strength',
            ],
            [
                'criteria_id' => 6,
                'name' => 'Endurance',
            ],

            // Antropometri
            [
                'criteria_id' => 7,
                'name' => 'Tinggi Badan',
            ],
            [
                'criteria_id' => 7,
                'name' => 'Wing Span',
            ],
            [
                'criteria_id' => 7,
                'name' => 'Berat Badan',
            ],
            [
                'criteria_id' => 7,
                'name' => 'Standing Reach',
            ],

            // Pemahaman Bermain
            [
                'criteria_id' => 8,
                'name' => 'Positioning',
            ],
            [
                'criteria_id' => 8,
                'name' => 'Decision Making',
            ],
            [
                'criteria_id' => 8,
                'name' => 'Court Vision',
            ],
            [
                'criteria_id' => 8,
                'name' => 'Offensive Movement',
            ],
            [
                'criteria_id' => 8,
                'name' => 'Defensive Awareness',
            ],
        ]);
    }
}
