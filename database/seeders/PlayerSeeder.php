<?php

namespace Database\Seeders;

use App\Modules\Admin\Models\Player;
use App\Modules\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PlayerSeeder extends Seeder
{
    public function run(): void
    {
        $playersData = [
            ['name' => 'Ahmad', 'email' => 'ahmadplayer@gmail.com', 'group_id' => 1],
            ['name' => 'Budi', 'email' => 'budi@gmail.com', 'group_id' => 1],
            ['name' => 'Candra', 'email' => 'candra@gmail.com', 'group_id' => 1],
            ['name' => 'Dedi', 'email' => 'dedi@gmail.com', 'group_id' => 1],
            ['name' => 'Eko', 'email' => 'eko@gmail.com', 'group_id' => 1],
            ['name' => 'Fandi', 'email' => 'fandi@gmail.com', 'group_id' => 2],
            ['name' => 'Gani', 'email' => 'gani@gmail.com', 'group_id' => 2],
            ['name' => 'Hadi', 'email' => 'hadi@gmail.com', 'group_id' => 2],
            ['name' => 'Indra', 'email' => 'indra@gmail.com', 'group_id' => 2],
            ['name' => 'Joni', 'email' => 'joni@gmail.com', 'group_id' => 2],
        ];

        foreach ($playersData as $index => $data) {
            $user = User::create([
                'name' => $data['name'],
                'username' => strtolower($data['name']),
                'email' => $data['email'],
                'password' => Hash::make('*ShieldPlayer'.($index + 1).'#'),
                'role' => 'player',
            ]);

            Player::create([
                'user_id' => $user->id,
                'name' => $data['name'],
                'birth_date' => '2010-01-01',
                'group_id' => $data['group_id'],
                'phone_number' => '081234567'.$index,
                'email' => $data['email'],
                'height' => 165.0 + $index,
                'weight' => 55.0 + $index,
                'parent_name' => 'Wali '.$data['name'],
                'parent_phone' => '089876543'.$index,
            ]);
        }
    }
}
