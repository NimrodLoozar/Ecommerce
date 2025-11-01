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
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->nullable()->constrained()->cascadeOnDelete(); // Nullable for global rates
            $table->string('state')->nullable(); // State/Province (for US, Canada, etc.)
            $table->decimal('rate', 5, 2); // Tax rate percentage
            $table->string('name'); // Tax name (VAT, Sales Tax, GST, etc.)
            $table->boolean('is_active')->default(true); // Active status
            $table->timestamps();

            // Indexes
            $table->index(['country_id', 'is_active']);
            $table->index(['country_id', 'state']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_rates');
    }
};
