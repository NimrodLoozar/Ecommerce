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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dealer_id')->constrained('dealer_profiles')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->foreignId('car_id')->constrained()->restrictOnDelete();
            $table->decimal('sale_amount', 10, 2); // Sale amount
            $table->decimal('commission_rate', 5, 2); // Commission percentage
            $table->decimal('commission_amount', 10, 2); // Calculated commission
            $table->enum('status', ['pending', 'approved', 'paid'])->default('pending');
            $table->timestamp('paid_at')->nullable(); // Payment timestamp
            $table->string('payment_method')->nullable(); // Payment method
            $table->string('payment_reference')->nullable(); // Payment reference number
            $table->timestamps();

            // Indexes
            $table->index(['dealer_id', 'status']);
            $table->index('status');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
