<?php

namespace Database\Seeders;

use App\Modules\Admin\Models\Coach;
use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CoachSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Akmal',
                'username' => 'Akmal',
                'email' => 'akmal@gmail.com',
                'password' => Hash::make('*ShieldCoach1#'),
                'role' => 'coach',
            ],
            [
                'name' => 'Ahmad',
                'username' => 'Ahmad',
                'email' => 'ahmad@gmail.com',
                'password' => Hash::make('*ShieldCoach2#'),
                'role' => 'coach',
            ],
            [
                'name' => 'Dani',
                'username' => 'Dani',
                'email' => 'dani@gmail.com',
                'password' => Hash::make('*ShieldCoach3#'),
                'role' => 'coach',
            ],
            [
                'name' => 'Nalen',
                'username' => 'Nalen',
                'email' => 'nalen@gmail.com',
                'password' => Hash::make('*ShieldCoach4#'),
                'role' => 'coach',
            ],
        ]);

        Coach::insert([
            [
                'name' => 'Akmal',
                'user_id' => 2,
                'birth_date' => '1990-05-10',
                'group_id' => 1,
                'position' => 'Head Coach  ',
                'license' => 'B License',
                'phone_number' => '08123456789',
                'email' => 'akmal@gmail.com',
                'is_master' => true,
            ],
            [
                'name' => 'Ahmad',
                'user_id' => 3,
                'birth_date' => '1985-12-15',
                'group_id' => 1,
                'position' => 'Assistant Coach',
                'license' => 'C License',
                'phone_number' => '08123456780',
                'email' => 'ahmad@gmail.com',
                'is_master' => false,
            ],
            [
                'name' => 'Dani',
                'user_id' => 4,
                'birth_date' => '1992-08-20',
                'group_id' => 2,
                'position' => 'Head Coach',
                'license' => 'C License',
                'phone_number' => '08123456781',
                'email' => 'dani@gmail.com',
                'is_master' => false,
            ],
            [
                'name' => 'Nalen',
                'user_id' => 5,
                'birth_date' => '1988-03-25',
                'group_id' => 2,
                'position' => 'Assistant Coach',
                'license' => 'C License',
                'phone_number' => '08123456782',
                'email' => 'nalen@gmail.com',
                'is_master' => false,
            ],
        ]);
    }
}
