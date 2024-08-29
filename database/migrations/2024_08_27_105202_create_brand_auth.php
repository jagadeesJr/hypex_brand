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
        Schema::create('brand_users', function (Blueprint $table) {
            $table->id();  // Primary key, auto-incrementing integer
            $table->string('first_name', 255);  // VARCHAR equivalent of TINYTEXT
            $table->string('last_name', 255)->nullable();   // VARCHAR equivalent of TINYTEXT
            $table->string('brand_name', 255);  // VARCHAR equivalent of TINYTEXT
            $table->string('email', 255);       // VARCHAR equivalent of TINYTEXT
            $table->string('phone_number', 255)->nullable();       // VARCHAR equivalent of TINYTEXT
            $table->string('country', 255)->nullable();       // VARCHAR equivalent of TINYTEXT
            $table->string('location', 255)->nullable();       // VARCHAR equivalent of TINYTEXT
            $table->string('password', 255);    // VARCHAR equivalent
            $table->boolean('status')->default(true);      // VARCHAR equivalent
            $table->timestamps();               // Adds created_at and updated_at columns
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_users');
    }
};
