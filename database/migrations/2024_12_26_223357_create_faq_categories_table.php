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
        Schema::create('faq-categories', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name', 100); // Name of the category
            $table->string('slug', 120)->unique(); // Unique slug for the category
            $table->text('description')->nullable(); // Optional description
            $table->string('icon', 50)->nullable(); // Optional icon
            $table->integer('sort_order')->default(0); // Sorting order
            $table->enum('status', ['active', 'inactive'])->default('active'); // Active or inactive status
            $table->timestamps(); // Created at and updated at
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq-categories');
    }
};
