<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparepartHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('sparepart_histories', function (Blueprint $table) {
            $table->id();
            $table->string('jurusan');
            $table->unsignedBigInteger('sparepart_id');
            $table->integer('jumlah_changed'); // Positive if added, negative if subtracted
            $table->string('action'); // Add or Subtract
            $table->timestamps();

            // Foreign key relationship
            $table->foreign('sparepart_id')->references('id_sparepart')->on('spareparts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sparepart_histories');
    }
}