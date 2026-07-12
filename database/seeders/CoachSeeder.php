<?php

namespace Database\Seeders;

use App\Modules\Admin\Services\Interfaces\IManagementDataService;
use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;

class CoachSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete existing coaches' users to avoid duplicate key issues
        User::where('role', 'coach')->delete();

        $managementDataService = app(IManagementDataService::class);

        $coaches = [
            [
                'name' => 'Hendra Tanuwijaya',
                'email' => 'hendra.tanuwijaya@gmail.com',
                'phone_number' => '628562000101',
                'birth_date' => '1980-01-01',
                'license' => 'Lisensi B Tahun 2026',
                'position' => 'Master Coach',
                'group_id' => 2, // KU 13-18
                'is_master' => true,
            ],
            [
                'name' => 'Siti Nurendah Masyiroh',
                'email' => 'siti.nurendah@gmail.com',
                'phone_number' => '085642186488',
                'birth_date' => '1983-09-26',
                'license' => 'Lisensi B Tahun 2026',
                'position' => 'Master Coach',
                'group_id' => 1, // KU 8-12
                'is_master' => true,
            ],
            [
                'name' => 'Sophia Ekaristi Darma',
                'email' => 'sophia.ekaristi@gmail.com',
                'phone_number' => '6289688738911',
                'birth_date' => '1997-07-29',
                'license' => 'Lisensi C Tahun 2026',
                'position' => 'Pelatih Kepala',
                'group_id' => 1, // KU 8-12
                'is_master' => false,
            ],
            [
                'name' => 'Samuel Senjaya Hirawan',
                'email' => 'samuel.senjaya@gmail.com',
                'phone_number' => '628562252913',
                'birth_date' => '1994-05-14',
                'license' => 'Lisensi C Tahun 2025',
                'position' => 'Pelatih Kepala',
                'group_id' => 2, // KU 13-18
                'is_master' => false,
            ],
            [
                'name' => 'Revel',
                'email' => 'revel@gmail.com',
                'phone_number' => '62895606072093',
                'birth_date' => '1995-03-20',
                'license' => 'Tidak Berlisensi',
                'position' => 'Assisten Pelatih',
                'group_id' => 1, // KU 8-12
                'is_master' => false,
            ],
            [
                'name' => 'Felice Elena',
                'email' => 'felice.elena@gmail.com',
                'phone_number' => '6281573060971',
                'birth_date' => '1996-08-10',
                'license' => 'Tidak Berlisensi',
                'position' => 'Assisten Pelatih',
                'group_id' => 2, // KU 13-18
                'is_master' => false,
            ],
        ];

        foreach ($coaches as $coachData) {
            $managementDataService->createCoach($coachData);
        }
    }
}
