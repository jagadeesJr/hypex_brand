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
        Schema::create('creators_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('creator_id');
            $table->string('name', 255);
            $table->string('price', 255)->nullable();
            $table->string('discount', 255)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Foreign key constraint
            // $table->foreign('creator_id')->references('id')->on('creator_users')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('creators_service');
    }
};
