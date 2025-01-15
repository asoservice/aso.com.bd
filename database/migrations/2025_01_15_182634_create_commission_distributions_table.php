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
        Schema::create('commission_distributions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('transaction_id');
            $table->unsignedBigInteger('marketer_id');
            $table->unsignedBigInteger('parent_marketer_id');
            $table->unsignedTinyInteger('level');
            $table->decimal('percentage', 5, 2);
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending');
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->foreign('transaction_id')
                ->references('id')
                ->on('marketer_transactions')
                ->onDelete('cascade');

            $table->foreign('marketer_id')
                ->references('id')
                ->on('marketer_users')
                ->onDelete('cascade');

            $table->foreign('parent_marketer_id')
                ->references('id')
                ->on('marketer_users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commission_distributions');
    }
};
