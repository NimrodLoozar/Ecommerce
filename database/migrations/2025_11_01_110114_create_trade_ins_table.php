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
        Schema::create('trade_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete(); // Linked when used in purchase
            $table->foreignId('brand_id')->constrained()->restrictOnDelete();
            $table->foreignId('car_model_id')->nullable()->constrained()->nullOnDelete();
            $table->year('year'); // Manufacturing year
            $table->unsignedInteger('mileage'); // Current mileage in km
            $table->enum('condition', ['excellent', 'good', 'fair', 'poor']);
            $table->string('vin')->nullable(); // VIN number
            $table->string('exterior_color');
            $table->enum('transmission', ['manual', 'automatic', 'semi_automatic']);
            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid', 'plugin_hybrid']);
            $table->text('description'); // Customer description
            $table->decimal('estimated_value', 10, 2)->nullable(); // Customer's estimated value
            $table->decimal('offered_value', 10, 2)->nullable(); // Dealer's offered value
            $table->decimal('final_value', 10, 2)->nullable(); // Final agreed value
            $table->enum('status', ['pending', 'under_review', 'offer_made', 'accepted', 'rejected', 'completed'])->default('pending');
            $table->text('admin_notes')->nullable(); // Internal notes
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete(); // Admin/dealer who reviewed
            $table->timestamp('reviewed_at')->nullable(); // Review timestamp
            $table->timestamp('expires_at')->nullable(); // Offer expiration date
            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index('status');
            $table->index('reviewed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trade_ins');
    }
};
