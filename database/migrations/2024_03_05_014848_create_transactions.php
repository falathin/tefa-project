<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama transaksi
            $table->decimal('purchase_price', 10, 2); // Harga beli
            $table->decimal('total_price', 10, 2); // Harga total
            $table->integer('discount')->default(0); // Diskon dalam angka (persentase atau nominal)
            $table->string('payment_method'); // Metode pembayaran
            $table->date('transaction_date'); // Tanggal transaksi
            $table->enum('transaction_type', ['purchase', 'sale']); // Jenis transaksi
            $table->enum('jurusan', ['TSM', 'TKRO', 'General']); // Jurusan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
}
