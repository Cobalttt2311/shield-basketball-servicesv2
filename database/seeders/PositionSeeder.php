<?php

namespace Database\Seeders;

use App\Modules\Coaches\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::insert([
            [
                'name' => 'Guard',
                'group_id' => 1,
            ],
            [
                'name' => 'Forward',
                'group_id' => 1,
            ],
            [
                'name' => 'Center',
                'group_id' => 1,
            ],
            [
                'name' => 'Shooting Guard',
                'group_id' => 2,
            ],
            [
                'name' => 'Point Guard',
                'group_id' => 2,
            ],
            [
                'name' => 'Small Forward',
                'group_id' => 2,
            ],
            [
                'name' => 'Power Forward',
                'group_id' => 2,
            ],
            [
                'name' => 'Center',
                'group_id' => 2,
            ],
        ]);
    }
}
