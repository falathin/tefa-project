<?php

// database/migrations/xxxx_xx_xx_create_sparepart_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparepartTransactionsTable extends Migration
{

    public function up()
    {
        Schema::create('sparepart_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sparepart_id'); // Ganti dengan tipe data yang sama
            $table->foreign('sparepart_id')->references('id_sparepart')->on('spareparts')->onDelete('cascade');
            $table->integer('jumlah');
            $table->decimal('harga_beli', 10, 2);
            $table->decimal('total_harga', 10, 2);
            $table->date('tanggal_transaksi');
            $table->enum('jenis_transaksi', ['purchase', 'sale']); // Jenis transaksi, bisa pembelian atau penjualan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sparepart_transactions');
    }
}