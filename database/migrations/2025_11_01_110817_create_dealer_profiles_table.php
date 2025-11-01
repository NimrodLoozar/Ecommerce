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
        Schema::create('dealer_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('company_name');
            $table->string('business_registration')->nullable(); // Registration number
            $table->string('tax_id')->nullable(); // Tax identification number
            $table->string('logo')->nullable(); // Company logo path
            $table->text('description')->nullable(); // Business description
            $table->string('phone');
            $table->string('website')->nullable(); // Website URL
            $table->decimal('commission_rate', 5, 2)->nullable(); // Commission percentage
            $table->string('subscription_plan')->nullable(); // Subscription tier
            $table->enum('status', ['pending', 'approved', 'suspended', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete(); // Admin who approved
            $table->timestamp('approved_at')->nullable(); // Approval timestamp
            $table->text('bank_account')->nullable(); // Bank details (should be encrypted in real app)
            $table->json('documents')->nullable(); // JSON array of document paths
            $table->timestamps();

            // Indexes
            $table->index('status');
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dealer_profiles');
    }
};
