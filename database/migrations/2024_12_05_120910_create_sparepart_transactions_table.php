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
            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')
                ->references('id')
                ->on('transactions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('sparepart_id');
            $table->foreign('sparepart_id')->references('id_sparepart')->on('spareparts')->onDelete('cascade');
            $table->integer('quantity');
            // $table->integer('price');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sparepart_transactions');
    }
}
