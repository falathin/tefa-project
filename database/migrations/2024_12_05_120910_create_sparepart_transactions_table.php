<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSparepartTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('sparepart_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sparepart_id');
            $table->foreign('sparepart_id')->references('id_sparepart')->on('spareparts')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->date('transaction_date');
            $table->enum('transaction_type', ['purchase', 'sale']);
            $table->enum('jurusan', ['TSM', 'TKRO', 'General']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sparepart_transactions');
    }
}