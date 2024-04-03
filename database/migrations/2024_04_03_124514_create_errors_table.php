<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('errors', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('data_id')->onDelete('cascade');
            $table->string('version');
            $table->string('status_code');

            $table->longText('status_message');
            $table->string('time');
            $table->string('cost');
            $table->string('tasks_count');
            $table->string('tasks_error');
            $table->json('tasks')->nullable();


            $table->timestamps();

            $table->foreign('data_id')->references('id')->on('from_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('errors');
    }
};
