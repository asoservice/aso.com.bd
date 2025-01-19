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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_id')->constrained('marketer_users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('target_amount', 10, 2)->nullable();
            $table->decimal('commission_rate', 5, 2);
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->integer('visits')->default(0);
            $table->integer('approved_orders')->default(0);
            $table->decimal('total_commission', 10, 2)->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->string('tracking_code')->unique();
            $table->string('landing_page_url')->nullable();
            $table->json('target_demographics')->nullable();
            $table->enum('status', ['draft', 'active', 'paused', 'completed', 'cancelled'])->default('draft');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
