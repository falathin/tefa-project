<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
class CreateServiceChecklistsTable extends Migration
{
    public function up()
    {
        Schema::create('service_checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->string('task');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
            $table->timestamp('added_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_checklists');
    }
}