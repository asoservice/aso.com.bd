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
        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->string('question', 500);
            $table->text('answer');
            $table->integer('sort_order')->default(0);
            $table->enum('status', ['published', 'draft', 'archived'])->default('draft');
            $table->integer('helpful_votes')->unsigned()->default(0);
            $table->integer('not_helpful_votes')->unsigned()->default(0);
            $table->timestamps();
            
            $table->foreignId('created_by')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('faq-categories', 'id')->cascadeOnDelete();

            $table->index('status');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faqs');
    }
};
