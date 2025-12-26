<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This method is executed when we run: php artisan migrate
     */
    public function up(): void
    {
        // Create 'categories' table
        Schema::create('categories', function (Blueprint $table) {

            // Auto-increment primary key (id)
            $table->id();

            // Category name column
            $table->string('name');

            // Adds created_at and updated_at columns
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * This method is executed when we run: php artisan migrate:rollback
     */
    public function down(): void
    {
        // Drop 'categories' table if it exists
        Schema::dropIfExists('categories');
    }
};
