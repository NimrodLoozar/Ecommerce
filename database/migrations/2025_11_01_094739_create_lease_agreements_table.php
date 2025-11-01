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
        Schema::create('lease_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Lease terms
            $table->integer('lease_duration'); // Duration in months
            $table->decimal('monthly_payment', 10, 2);
            $table->decimal('down_payment', 12, 2)->default(0);
            $table->integer('annual_mileage_limit')->default(15000); // Allowed mileage per year
            $table->decimal('excess_mileage_charge', 8, 2)->default(0.15); // Charge per km over limit

            // Dates
            $table->date('start_date');
            $table->date('end_date');

            // Status
            $table->enum('status', ['pending', 'active', 'completed', 'terminated'])->default('pending');

            // Contract
            $table->string('contract_file')->nullable(); // Path to signed contract PDF

            $table->timestamps();

            // Indexes
            $table->index('order_id');
            $table->index('car_id');
            $table->index('user_id');
            $table->index('status');
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lease_agreements');
    }
};
