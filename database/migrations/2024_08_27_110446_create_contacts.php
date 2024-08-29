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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('business_id');
            $table->unsignedBigInteger('group_id')->nullable();
            $table->unsignedBigInteger('segment_id')->nullable();
            $table->string('name');
            $table->string('phone_number');
            $table->string('location')->nullable();
            $table->string('age')->nullable();
            $table->string('email')->nullable();
            $table->string('type')->nullable();
            $table->timestamps();

            // Foreign key constraints
            // $table->foreign('business_id')->references('id')->on('brand_business')->onDelete('cascade');
            // $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
            // $table->foreign('segment_id')->references('id')->on('segments')->onDelete('set null');
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
