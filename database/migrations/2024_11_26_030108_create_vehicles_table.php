<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('license_plate');
            $table->string('vehicle_type')->nullable(); // Changed column name to 'vehicle_type'
            $table->string('engine_code')->nullable();
            $table->string('color')->nullable();
            $table->integer('production_year')->nullable();
            $table->string('image')->nullable();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}