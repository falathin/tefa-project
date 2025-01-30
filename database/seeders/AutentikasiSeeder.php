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
                'name' => 'Nama orang',
                'email' => 'iteens.tefa@gmail.com',
                'level' => 'engineer',
                'jurusan' => 'General',
                'password' => bcrypt('asu12345')
            ],
            [
                'name' => 'Nama orang',
                'email' => 'admin@gmail.com',
                'level' => 'admin',
                'jurusan' => 'TKRO',
                'password' => bcrypt('asu12345')
            ],
            [
                'name' => 'Nama orang',
                'email' => 'bendahara@gmail.com',
                'level' => 'bendahara',
                'jurusan' => 'General',
                'password' => bcrypt('asu12345')
            ],
            [
                'name' => 'Nama orang',
                'email' => 'kasir@gmail.com',
                'level' => 'kasir',
                'jurusan' => 'TKRO',
                'password' => bcrypt('asu12345')
            ],
            [
                'name' => 'Nama orang',
                'email' => 'adminTbsm@gmail.com',
                'level' => 'admin',
                'jurusan' => 'TSM',
                'password' => bcrypt('asu12345')
            ],
        ];

        foreach ($userData as $key => $val) {
            User::create($val);
        }

        EmergencyPassword::create([
            'emergency_password' => 'SUPER4060$1000;VS{StallerJade};'
        ]);
    }
}

