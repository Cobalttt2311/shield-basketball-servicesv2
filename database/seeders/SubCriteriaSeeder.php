<?php

namespace Database\Seeders;

use App\Modules\Coaches\Models\Criteria;
use App\Modules\Coaches\Models\CriteriaSet;
use App\Modules\Coaches\Models\SubCriteria;
use Illuminate\Database\Seeder;

class SubCriteriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $set1 = CriteriaSet::where('group_id', 1)->latest('id')->first();
        $set2 = CriteriaSet::where('group_id', 2)->latest('id')->first();

        if (! $set1 || ! $set2) {
            throw new \Exception('Criteria sets must be seeded first.');
        }

        $c1 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Skill')->first()?->id;
        $c2 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Fisik')->first()?->id;
        $c3 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Antropometri')->first()?->id;
        $c4 = Criteria::where('criteria_set_id', $set1->id)->where('name', 'Pemahaman Bermain')->first()?->id;

        $c5 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Skill')->first()?->id;
        $c6 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Fisik')->first()?->id;
        $c7 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Antropometri')->first()?->id;
        $c8 = Criteria::where('criteria_set_id', $set2->id)->where('name', 'Pemahaman Bermain')->first()?->id;

        SubCriteria::insert([
            // KU 8-12
            // Skill
            [
                'criteria_id' => $c1,
                'name' => 'Ball Handling',
            ],
            [
                'criteria_id' => $c1,
                'name' => 'Passing',
            ],
            [
                'criteria_id' => $c1,
                'name' => 'Shooting',
            ],
            [
                'criteria_id' => $c1,
                'name' => 'Defense',
            ],

            // Fisikk
            [
                'criteria_id' => $c2,
                'name' => 'Speed',
            ],
            [
                'criteria_id' => $c2,
                'name' => 'Agility',
            ],
            [
                'criteria_id' => $c2,
                'name' => 'Coordination',
            ],
            [
                'criteria_id' => $c2,
                'name' => 'Vertical Jump',
            ],

            // Antropometri
            [
                'criteria_id' => $c3,
                'name' => 'Tinggi Badan',
            ],
            [
                'criteria_id' => $c3,
                'name' => 'Wing Span',
            ],
            [
                'criteria_id' => $c3,
                'name' => 'Berat Badan',
            ],
            [
                'criteria_id' => $c3,
                'name' => 'Standing Reach',
            ],

            // Pemahaman Bermain
            [
                'criteria_id' => $c4,
                'name' => 'Positioning',
            ],
            [
                'criteria_id' => $c4,
                'name' => 'Decision Making',
            ],
            [
                'criteria_id' => $c4,
                'name' => 'Movement',
            ],
            [
                'criteria_id' => $c4,
                'name' => 'Spacing',
            ],

            // KU 13-18
            // Skill
            [
                'criteria_id' => $c5,
                'name' => 'Ball Handling',
            ],
            [
                'criteria_id' => $c5,
                'name' => 'Passing',
            ],
            [
                'criteria_id' => $c5,
                'name' => 'Shooting',
            ],
            [
                'criteria_id' => $c5,
                'name' => 'Defense',
            ],
            [
                'criteria_id' => $c5,
                'name' => 'Rebound',
            ],
            [
                'criteria_id' => $c5,
                'name' => 'Post Move',
            ],

            // Fisikk
            [
                'criteria_id' => $c6,
                'name' => 'Speed',
            ],
            [
                'criteria_id' => $c6,
                'name' => 'Agility',
            ],
            [
                'criteria_id' => $c6,
                'name' => 'Vertical Jump',
            ],
            [
                'criteria_id' => $c6,
                'name' => 'Strength',
            ],
            [
                'criteria_id' => $c6,
                'name' => 'Endurance',
            ],

            // Antropometri
            [
                'criteria_id' => $c7,
                'name' => 'Tinggi Badan',
            ],
            [
                'criteria_id' => $c7,
                'name' => 'Wing Span',
            ],
            [
                'criteria_id' => $c7,
                'name' => 'Berat Badan',
            ],
            [
                'criteria_id' => $c7,
                'name' => 'Standing Reach',
            ],

            // Pemahaman Bermain
            [
                'criteria_id' => $c8,
                'name' => 'Positioning',
            ],
            [
                'criteria_id' => $c8,
                'name' => 'Decision Making',
            ],
            [
                'criteria_id' => $c8,
                'name' => 'Court Vision',
            ],
            [
                'criteria_id' => $c8,
                'name' => 'Offensive Movement',
            ],
            [
                'criteria_id' => $c8,
                'name' => 'Defensive Awareness',
            ],
        ]);
    }
}
