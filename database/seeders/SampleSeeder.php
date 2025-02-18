<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SampleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $spareparts = [];
        
        for ($i = 1; $i <= 30; $i++) {
            $harga_beli = rand(50000, 500000);
            $harga_jual = $harga_beli + rand(10000, 50000);
            
            $spareparts[] = [
                'nama_sparepart' => 'Sparepart ' . $i,
                'jumlah' => rand(1, 50),
                'harga_beli' => $harga_beli,
                'harga_jual' => $harga_jual,
                'keuntungan' => $harga_jual - $harga_beli,
                'tanggal_masuk' => Carbon::now()->subDays(rand(1, 60)),
                'tanggal_keluar' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,
                'deskripsi' => 'Deskripsi untuk Sparepart ' . $i,
                'jurusan' => 'TSM',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        DB::table('spareparts')->insert($spareparts);
    }
}
