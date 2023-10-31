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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('ssd')->nullable();
            $table->string('zone')->nullable();
            $table->string('length')->nullable();
            $table->string('width')->nullable();
            $table->string('img_name')->nullable();
            $table->string('social_id')->nullable();
            $table->string('social_type')->nullable();
            $table->string('phone')->nullable()->unique();
            $table->timestamp('phone_verify_at')->nullable();
            $table->integer('phone_verify_code')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
