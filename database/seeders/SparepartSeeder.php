<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Sparepart;

class SparepartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $csvFile = fopen(base_path("database/data/spareparts.csv"),"r");
        $firstLine = true;
        // $data = fgetcsv($csvFile,200,";");
        while (($data = fgetcsv($csvFile,200,";")) !== FALSE) {
            if(!$firstLine) {
                Sparepart::create([
                    "nama_sparepart"    => $data['0'],
                    "jumlah"            => $data['1'],
                    "harga_beli"        => $data['2'],
                    "harga_jual"        => $data['3'],
                    "keuntungan"        => $data['4'],
                    "tanggal_masuk"     => $data['5'],
                    "deskripsi"         => $data['6'],
                ]);
            }
            $firstLine = false;
        }
    }
}