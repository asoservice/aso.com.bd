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
        Schema::create('video_tutorials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('tutorial_categories')->onDelete('cascade');
            $table->string('title', 255);
            $table->string('slug', 275)->unique();
            $table->text('description')->nullable();
            $table->text('embeded_url')->nullable();
            $table->integer('duration')->unsigned()->nullable();
            $table->string('thumbnail_url', 500)->nullable();
            $table->bigInteger('views_count')->unsigned()->default(0);
            $table->enum('status', ['published', 'draft', 'archived'])->default('draft');
            $table->foreignId('created_by')->constrained('support_users', 'user_id');
            $table->timestamps();
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_tutorials');
    }
};
