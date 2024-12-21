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
                'jurusan' => 'TBSM',
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

// Kegiatan untuk hari Kamis 
// - Melanjutkan pembuatan halaman profil, fitur ubah password, email, dan nama. Referensi dari halaman profil template-login-breeze
// - Buat komponen alert dialog
// - Buat fitur tambah akun (khusus level engineer dan admin)
// - Integrasikan komponen alert dialog ke fitur logout untuk konfirmasi logout

// Catatan
// - saat menggunakan mode handphone, tampilan foto profil tidak tampil karenanya tidak bisa mengakses halaman profil
