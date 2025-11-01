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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('brand_id')->constrained()->onDelete('cascade');
            $table->foreignId('car_model_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->foreignId('condition_id')->constrained()->onDelete('restrict');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Dealer/Admin who listed

            $table->string('vin')->unique()->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');

            // Car specifications
            $table->year('year');
            $table->integer('mileage')->default(0); // in kilometers
            $table->decimal('price', 12, 2);
            $table->decimal('lease_price_monthly', 10, 2)->nullable();

            $table->enum('fuel_type', ['petrol', 'diesel', 'electric', 'hybrid', 'plugin_hybrid']);
            $table->enum('transmission', ['manual', 'automatic', 'semi_automatic']);
            $table->decimal('engine_size', 4, 1)->nullable(); // liters
            $table->integer('horsepower')->nullable();

            $table->string('exterior_color')->nullable();
            $table->string('interior_color')->nullable();
            $table->integer('doors')->default(4);
            $table->integer('seats')->default(5);

            // Inventory management
            $table->integer('stock_quantity')->default(1);
            $table->enum('status', ['available', 'reserved', 'sold', 'pending'])->default('available');

            // Features
            $table->boolean('is_featured')->default(false);
            $table->unsignedBigInteger('views_count')->default(0);

            // SEO
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Indexes for search and filtering
            $table->index('brand_id');
            $table->index('car_model_id');
            $table->index('category_id');
            $table->index('condition_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index(['price', 'year', 'mileage']);
            $table->index('fuel_type');
            $table->index('transmission');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
