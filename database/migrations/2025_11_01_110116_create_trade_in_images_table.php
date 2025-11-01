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
        Schema::create('trade_in_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trade_in_id')->constrained()->cascadeOnDelete();
            $table->string('image_path'); // Image file path
            $table->enum('image_type', ['exterior', 'interior', 'damage', 'documents']); // Type of image
            $table->unsignedInteger('sort_order')->default(0); // Display order
            $table->timestamps();

            // Indexes
            $table->index(['trade_in_id', 'image_type']);
            $table->index(['trade_in_id', 'sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_in_images');
    }
};
