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
        Schema::create('marketer_tiers', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('level');
            $table->decimal('percentage', 5, 2);
            $table->text('description');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique('level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_comission_rates');
    }
};
