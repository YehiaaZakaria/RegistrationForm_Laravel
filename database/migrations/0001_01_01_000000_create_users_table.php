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
        Schema::create('user2', function (Blueprint $table) {
            $table->increments('id');
            $table->string('full_name');
            $table->string('user_name')->unique();
            $table->string('birthdate');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('password');
            $table->string('user_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user2');
    }
};
