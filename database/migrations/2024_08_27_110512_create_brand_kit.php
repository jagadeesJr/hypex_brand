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
        Schema::create('brand_kit', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->string('font_family')->nullable();
            $table->string('color_code')->nullable();
            $table->json('images')->nullable();
            $table->timestamps();

            // Foreign key constraint
            // $table->foreign('business_id')->references('id')->on('brand_business')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_kit');
    }
};
