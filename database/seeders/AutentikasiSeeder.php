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
                'name' => 'Engineer',
                'email' => 'iteens.tefa@gmail.com',
                'level' => 'engineer',
                'jurusan' => 'TKRO',
                'password' => bcrypt('asu12345')
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'level' => 'admin',
                'jurusan' => 'TKRO',
                'password' => bcrypt('asu12345')
            ],
            [
                'name' => 'Bendahara',
                'email' => 'bendahara@gmail.com',
                'level' => 'bendahara',
                'jurusan' => 'TKRO',
                'password' => bcrypt('asu12345')
            ],
            [
                'name' => 'Kasir',
                'email' => 'kasir@gmail.com',
                'level' => 'kasir',
                'jurusan' => 'TKRO',
                'password' => bcrypt('asu12345')
            ],
            [
                'name' => 'admin tbsm',
                'email' => 'AdminTbsm@gmail.com',
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

