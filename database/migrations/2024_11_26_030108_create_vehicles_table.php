<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('license_plate');
            $table->string('type'); // Ensure 'type' is required
            $table->string('color')->nullable();
            $table->string('production_year')->nullable();
            $table->string('engine_code')->nullable();
            $table->foreignId('customer_id')
                ->constrained('customers')
                ->onDelete('cascade'); // Foreign key to customers
            $table->string('image')->nullable(); // New column for vehicle image
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicles');
    }
}