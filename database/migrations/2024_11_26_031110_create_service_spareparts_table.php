<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->unsignedBigInteger('sparepart_id'); // Use unsignedBigInteger for matching with spareparts id
            $table->foreign('sparepart_id') // Explicitly define the foreign key constraint
                ->references('id_sparepart')
                ->on('spareparts')
                ->cascadeOnDelete(); // Ensure the foreign key is set correctly
            $table->integer('quantity');
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