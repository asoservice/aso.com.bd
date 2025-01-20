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
        Schema::create('click_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_link_id')->constrained()->onDelete('cascade');

            // Visitor Information
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('device_type', 50)->nullable();

            // Location Data
            $table->string('country', 2)->nullable();
            $table->string('region', 100)->nullable();
            $table->string('city', 100)->nullable();

            // Additional Tracking Data
            $table->boolean('is_unique')->default(false);
            $table->json('metadata')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('marketer_link_id');
            $table->index('created_at');
            $table->index(['country', 'region', 'city']);
            $table->index('device_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('click_trackings');
    }
};
