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
        Schema::create('marketer_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_withdrawal_amount', 15, 2)->default(100.00);
            $table->decimal('min_earning_requirement', 15, 2)->default(100.00);
            $table->integer('commission_waiting_days')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketer_settings');
    }
};
