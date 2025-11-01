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
        Schema::table('cars', function (Blueprint $table) {
            $table->foreignId('dealer_id')->nullable()->after('user_id')->constrained('dealer_profiles')->nullOnDelete();
            $table->index('dealer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['dealer_id']);
            $table->dropIndex(['dealer_id']);
            $table->dropColumn('dealer_id');
        });
    }
};
