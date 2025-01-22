<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('user_id'); // ID pengguna yang membuat post
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('jurusan');
            $table->string('name');
            $table->string('contact')->nullable();
            $table->text('address')->nullable();
            $table->enum('jurusan', ['TSM', 'TKRO']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
