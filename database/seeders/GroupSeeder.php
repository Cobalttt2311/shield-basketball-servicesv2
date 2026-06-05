<?php

namespace Database\Seeders;

use App\Modules\Admin\Models\Group;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Group::insert([
            [
                'age_group' => 'KU 8-12',
            ],
            [
                'age_group' => 'KU 13-18',
            ],
        ]);
    }
}
