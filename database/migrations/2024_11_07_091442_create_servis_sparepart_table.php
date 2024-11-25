<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServisSparepartTable extends Migration
{
    public function up()
    {
        Schema::create('servis_sparepart', function (Blueprint $table) {
            $table->id();
            $table->foreignId('servis_id')->constrained('servis', 'id_servis')->onDelete('cascade');
            $table->foreignId('sparepart_id')->constrained('spareparts', 'id_sparepart')->onDelete('cascade');
            $table->integer('jumlah');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servis_sparepart');
    }
}