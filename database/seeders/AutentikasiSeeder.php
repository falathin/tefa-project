<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\EmergencyPassword;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AutentikasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userData = [
            [
                'name' => 'Annida',
                'email' => 'iteens.tefa@gmail.com',
                'level' => 'engineer',
                'jurusan' => 'TSM',
                'phone_number' => '087712134885',
                'password' => bcrypt('admin123')
            ],
            [
                'name' => 'Ibrahim',
                'email' => 'admin@gmail.com',
                'level' => 'admin',
                'jurusan' => 'TKRO',
                'phone_number' => '087712134885',
                'password' => bcrypt('admin123')
            ],
            [
                'name' => 'Bayu',
                'email' => 'bendahara@gmail.com',
                'level' => 'bendahara',
                'jurusan' => 'General',
                'phone_number' => '086656789002',
                'password' => bcrypt('admin123')
            ],
            [
                'name' => 'Seno',
                'email' => 'kasir@gmail.com',
                'level' => 'kasir',
                'jurusan' => 'TKRO',
                'phone_number' => '086543320087',
                'password' => bcrypt('admin123')
            ],
            [
                'name' => 'Yani',
                'email' => 'adminTbsm@gmail.com',
                'level' => 'admin',
                'jurusan' => 'TSM',
                'phone_number' => '087544346666',
                'password' => bcrypt('admin123')
            ],
        ];

        foreach ($userData as $key => $val) {
            User::create($val);
        }

        EmergencyPassword::create([
            'emergency_password' => 'theMostPowerfulIntel'
        ]);
    }
}
