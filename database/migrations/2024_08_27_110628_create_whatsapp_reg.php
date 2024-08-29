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
        Schema::create('whatsapp_reg', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Foreign key constraint (optional)
            // $table->foreign('business_id')->references('id')->on('businesses')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_reg');
    }
};
