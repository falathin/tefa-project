<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateServiceSparepartsTable extends Migration
{
    public function up()
    {
        Schema::create('service_spareparts', function (Blueprint $table) {
            $table->id(); // Primary key (bigint)
            $table->foreignId('service_id')
                ->constrained('services') // Relasi ke tabel services
                ->cascadeOnDelete();
            $table->unsignedBigInteger('sparepart_id')->nullable(); // Use unsignedBigInteger for matching with spareparts id
            $table->foreign('sparepart_id') // Explicitly define the foreign key constraint
                ->references('id_sparepart')
                ->on('spareparts')
                ->nullOnDelete();
                // ->cascadeOnDelete(); // Ensure the foreign key is set correctly
            $table->string('nama_sparepart')->nullable();
            $table->string('spek')->nullable();
            $table->integer('harga_jual')->nullable();
            $table->integer('quantity')->unsigned();;
            $table->timestamps();
        });
    }

    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Disable foreign key checks
        Schema::dropIfExists('service_spareparts');
        Schema::dropIfExists('services');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('spareparts');
        DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Enable foreign key checks
    }
}
