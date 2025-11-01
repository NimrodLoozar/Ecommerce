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
        Schema::create('dealer_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained('dealer_profiles')->cascadeOnDelete();
            $table->string('period', 7); // Date period (YYYY-MM)
            $table->unsignedInteger('total_listings')->default(0); // Total active listings
            $table->unsignedInteger('total_views')->default(0); // Total car views
            $table->unsignedInteger('total_inquiries')->default(0); // Total inquiries received
            $table->unsignedInteger('total_sales')->default(0); // Total cars sold
            $table->decimal('total_revenue', 12, 2)->default(0.00); // Total revenue
            $table->decimal('commission_owed', 10, 2)->default(0.00); // Commission to be paid
            $table->timestamps();

            // Unique constraint on dealer + period
            $table->unique(['dealer_id', 'period']);

            // Indexes
            $table->index('period');
            $table->index(['dealer_id', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_analytics');
    }
};
