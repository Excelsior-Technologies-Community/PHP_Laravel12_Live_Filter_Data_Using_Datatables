<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method runs when we execute: php artisan migrate
     */
    public function up(): void
    {
        // Create 'products' table
        Schema::create('products', function (Blueprint $table) {

            // Auto-increment primary key
            $table->id();

            // Product name
            $table->string('name');

            // Product description (optional)
            $table->text('description')->nullable(); // Product description

            // Product price with 2 decimal points
            $table->decimal('price', 8, 2);

            // Foreign key reference to categories table
            // category_id → categories.id
            // cascadeOnDelete means: if category is deleted, its products are also deleted
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            // Adds created_at and updated_at columns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * This method runs when we execute: php artisan migrate:rollback
     */
    public function down(): void
    {
        // Drop 'products' table if it exists
        Schema::dropIfExists('products');
    }
};
