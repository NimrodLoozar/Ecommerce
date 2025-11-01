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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 2)->unique(); // ISO country code (NL, DE, BE)
            $table->string('name'); // Netherlands, Germany, Belgium
            $table->foreignId('currency_id')->constrained()->restrictOnDelete(); // Default currency
            $table->decimal('tax_rate', 5, 2)->default(0.00); // Default tax rate percentage
            $table->string('phone_code', 10); // International phone code (+31, +49, +32)
            $table->boolean('is_active')->default(true); // Active for delivery
            $table->unsignedInteger('sort_order')->default(0); // Display order
            $table->timestamps();

            // Indexes
            $table->index('code');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
