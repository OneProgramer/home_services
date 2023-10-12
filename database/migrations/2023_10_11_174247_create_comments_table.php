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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->bigInteger('worker_id')->unsigned()->index()->nullable();
            $table->bigInteger('job_id')->unsigned()->index()->nullable();
            $table->text('description');
            $table->boolean('accept')->nullable();
            $table->integer('price');
            $table->integer('days');
            $table->foreign('worker_id')->references('id')->on('workers')->onDelete('cascade');
            $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
