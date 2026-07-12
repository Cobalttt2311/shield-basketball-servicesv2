<?php

namespace Database\Seeders;

use App\Modules\Admin\Services\Interfaces\IManagementDataService;
use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing players' users to avoid duplicate key issues
        User::where('role', 'player')->delete();

        $managementDataService = app(IManagementDataService::class);

        $players = [
            [
                'name' => 'Bradley Dean Tirta',
                'birth_date' => '2011-02-15',
                'group_id' => 2, // KU 13-18
                'height' => 186.0,
                'weight' => 75.0,
            ],
            [
                'name' => 'Vincentius Darrel Emmanuell',
                'birth_date' => '2010-11-26',
                'group_id' => 2,
                'height' => 167.0,
                'weight' => 69.0,
            ],
            [
                'name' => 'Zemuel Reynard Passage',
                'birth_date' => '2011-01-02',
                'group_id' => 2,
                'height' => 158.0,
                'weight' => 65.0,
            ],
            [
                'name' => 'Kenneth Christian Ginanjar',
                'birth_date' => '2011-01-25',
                'group_id' => 2,
                'height' => 171.0,
                'weight' => 67.0,
            ],
            [
                'name' => 'Emmanuel Jason Kurniawan',
                'birth_date' => '2011-08-22',
                'group_id' => 2,
                'height' => 170.0,
                'weight' => 80.0,
            ],
            [
                'name' => 'Marvel Cornelius Wijaya',
                'birth_date' => '2010-02-07',
                'group_id' => 2,
                'height' => 168.0,
                'weight' => 62.0,
            ],
            [
                'name' => 'Christian Jefferson Gunawan',
                'birth_date' => '2010-02-27',
                'group_id' => 2,
                'height' => 180.0,
                'weight' => 67.0,
            ],
            [
                'name' => 'Satria Gilang Pradana',
                'birth_date' => '2010-09-06',
                'group_id' => 2,
                'height' => 179.0,
                'weight' => 62.0,
            ],
            [
                'name' => 'Barron William Sinclair',
                'birth_date' => '2010-09-16',
                'group_id' => 2,
                'height' => 170.0,
                'weight' => 70.0,
            ],
            [
                'name' => 'Nelson Jeremy Wijaya',
                'birth_date' => '2010-03-19',
                'group_id' => 2,
                'height' => 182.0,
                'weight' => 79.0,
            ],
            [
                'name' => 'Yunlun Chiang',
                'birth_date' => '2015-03-24',
                'group_id' => 1, // KU 8-12
                'height' => 135.0, // dummy
                'weight' => 30.0,  // dummy
            ],
            [
                'name' => 'Chong Renxi',
                'birth_date' => '2014-04-04',
                'group_id' => 1,
                'height' => 133.0,
                'weight' => 29.0,
            ],
            [
                'name' => 'Jamie Aldrich Martono',
                'birth_date' => '2014-08-03',
                'group_id' => 1,
                'height' => 136.0,
                'weight' => 31.0,
            ],
            [
                'name' => 'Chong Renyu',
                'birth_date' => '2014-04-04',
                'group_id' => 1,
                'height' => 134.0,
                'weight' => 30.0,
            ],
            [
                'name' => 'Leander Oey Vince Elmer',
                'birth_date' => '2014-02-19',
                'group_id' => 1,
                'height' => 132.0,
                'weight' => 28.0,
            ],
            [
                'name' => 'James Francis Chandra',
                'birth_date' => '2014-04-07',
                'group_id' => 1,
                'height' => 137.0,
                'weight' => 32.0,
            ],
            [
                'name' => 'Colin Bradley Winata',
                'birth_date' => '2014-11-11',
                'group_id' => 1,
                'height' => 135.0,
                'weight' => 30.0,
            ],
            [
                'name' => 'Jayden Jonathan Arnold',
                'birth_date' => '2015-12-08',
                'group_id' => 1,
                'height' => 130.0,
                'weight' => 27.0,
            ],
            [
                'name' => 'Galan Ihsan Gunadi',
                'birth_date' => '2015-07-10',
                'group_id' => 1,
                'height' => 137.0,
                'weight' => 37.0,
            ],
            [
                'name' => 'Aksell Sastraputera',
                'birth_date' => '2015-08-07',
                'group_id' => 1,
                'height' => 128.0,
                'weight' => 27.0,
            ],
        ];

        foreach ($players as $index => $playerData) {
            // Generate dummy details for parent & contact
            $emailName = strtolower(str_replace(' ', '.', $playerData['name']));

            $fullData = array_merge([
                'email' => $emailName.'@gmail.com',
                'phone_number' => '0812'.str_pad($index + 1, 8, '0', STR_PAD_LEFT),
                'parent_name' => $playerData['name'].' Parent',
                'parent_phone' => '0898'.str_pad($index + 1, 8, '0', STR_PAD_LEFT),
            ], $playerData);

            $managementDataService->createPlayer($fullData);
        }
    }
}
