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
            $table->decimal('purchase_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->date('transaction_date');
            $table->enum('transaction_type', ['purchase', 'sale']);
            $table->enum('jurusan', ['TSM', 'TKRO', 'General']);
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
};
