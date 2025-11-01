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
        Schema::create('delivery_zones', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Zone name (e.g., "EU Zone 1", "Domestic")
            $table->json('countries'); // JSON array of country IDs
            $table->decimal('delivery_fee', 10, 2); // Base delivery fee
            $table->decimal('free_delivery_threshold', 10, 2)->nullable(); // Order amount for free delivery
            $table->unsignedInteger('estimated_days_min')->default(1); // Minimum delivery days
            $table->unsignedInteger('estimated_days_max')->default(7); // Maximum delivery days
            $table->boolean('is_active')->default(true); // Active status
            $table->timestamps();

            // Indexes
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_zones');
    }
};
