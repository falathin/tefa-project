<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparepartsTable extends Migration
{
    public function up()
    {
        Schema::create('spareparts', function (Blueprint $table) {
            $table->id('id_sparepart'); // Primary key
            $table->string('nama_sparepart');
            $table->string('spek');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->decimal('keuntungan', 15, 2)->computedAs('harga_jual - harga_beli');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('jurusan', ['TSM', 'TKRO', 'General']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spareparts');
    }
}