<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->text('complaint');
            $table->integer('current_mileage');
            $table->decimal('service_fee', 10, 2);
            $table->date('service_date');
            $table->decimal('total_cost', 10, 2);
            $table->decimal('payment_received', 10, 2);
            $table->decimal('change', 10, 2);
            $table->enum('service_type', ['light', 'medium', 'heavy'])->default('light');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
}