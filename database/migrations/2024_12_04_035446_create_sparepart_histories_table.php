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
            $table->unsignedBigInteger('sparepart_id');
            $table->string('field_changed');
            $table->integer('old_value')->nullable();
            $table->integer('new_value')->nullable();
            $table->string('action'); // update | add | delete
            $table->unsignedBigInteger('user_id'); // ID user yang melakukan perubahan (opsional)
            $table->timestamps(); // created_at dan updated_at
            // Foreign key relationship
            $table->foreign('sparepart_id')->references('id_sparepart')->on('spareparts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('sparepart_histories');
    }
}