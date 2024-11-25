<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePelanggansTable extends Migration
{
    public function up()
    {
        Schema::create('pelanggans', function (Blueprint $table) {
            $table->bigIncrements('id_pelanggan');  // Primary key
            $table->string('nama_pelanggan');
            $table->string('kontak');
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pelanggans');
    }
}