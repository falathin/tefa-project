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
            $table->decimal('service_fee', 10, 2)->nullable()->default(0);
            $table->date('service_date'); // Tanggal servis
            $table->decimal('total_cost', 10, 2)->nullable(); // Total biaya
            $table->decimal('payment_received', 10, 2)->nullable(); // Pembayaran diterima
            $table->decimal('change', 10, 2)->nullable(); // Kembalian pembayaran
            $table->enum('service_type', ['light', 'medium', 'heavy'])->default('light'); // Jenis servis
            $table->boolean('status')->default(false); // false = in progress, true = completed
            $table->text('additional_notes')->nullable(); // Deskripsi tambahan terkait servis
            $table->string('technician_name')->nullable(); // Nama teknisi yang menangani
            $table->string('payment_proof')->nullable(); // Path bukti pembayaran
            $table->enum('jurusan', ['TSM', 'TKRO', 'General']);
            $table->integer('diskon')->default(0); // Diskon dalam bentuk nominal (bukan persentase)
            $table->string('payment_method')->default('cash');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('services');
    }
}