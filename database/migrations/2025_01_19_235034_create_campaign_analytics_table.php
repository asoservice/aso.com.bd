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
        Schema::create('campaign_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('daily_visits')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->integer('approved_orders')->default(0);
            $table->decimal('daily_commission', 10, 2)->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->integer('bounce_rate')->default(0);
            $table->integer('average_time_spent')->default(0);
            $table->json('traffic_sources')->nullable();
            $table->json('device_breakdown')->nullable();
            $table->timestamps();

            $table->unique(['campaign_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_analytics');
    }
};
