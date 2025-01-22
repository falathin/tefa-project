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
            $table->text('complaint'); // Keluhan servis
            $table->integer('current_mileage'); // Kilometer saat servis
            $table->decimal('service_fee', 10, 2); // Biaya servis
            $table->date('service_date'); // Tanggal servis
            $table->decimal('total_cost', 10, 2); // Total biaya
            $table->decimal('payment_received', 10, 2); // Pembayaran diterima
            $table->decimal('change', 10, 2); // Kembalian pembayaran
            $table->enum('service_type', ['light', 'medium', 'heavy'])->default('light'); // Jenis servis
            $table->string('status')->default('in progress'); // Status servis: 'in progress', 'completed', 'pending'
            $table->text('additional_notes')->nullable(); // Deskripsi tambahan terkait servis
            $table->string('technician_name')->nullable(); // Nama teknisi yang menangani
            $table->string('payment_proof')->nullable(); // Path bukti pembayaran
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
}