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
        Schema::create('conversion_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_link_id')->constrained()->onDelete('cascade');
            $table->string('order_id')->unique();
            $table->foreignId('customer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');

            // Financial Data
            $table->decimal('amount', 10, 2);
            $table->decimal('commission', 10, 2);

            // Additional Information
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->json('metadata')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('marketer_link_id');
            $table->index('order_id');
            $table->index('created_at');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversion_trackings');
    }
};
