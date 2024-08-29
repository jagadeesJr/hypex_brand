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
        Schema::create('brand_business', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->unsignedBigInteger('brand_id');
            $table->text('description')->nullable();
            $table->json('branch_area')->nullable();
            $table->json('categories')->nullable();
            $table->json('subcategories')->nullable();
            $table->string('target_audience')->nullable();
            $table->json('business_hours')->nullable();
            $table->longText('interests')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();

            // Foreign key constraint (if you have a brands table)
            // $table->foreign('brand_id')->references('id')->on('brand_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brand_business');
    }
};
