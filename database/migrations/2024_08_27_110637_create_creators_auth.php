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
        Schema::create('creator_users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 255);
            $table->string('last_name', 255)->nullable();
            $table->string('phone_number', 255)->nullable();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->string('country', 255)->nullable(); // VARCHAR equivalent of TINYTEXT
            $table->string('location', 255)->nullable(); // VARCHAR equivalent of TINYTEXT
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creator_users');
    }
};
