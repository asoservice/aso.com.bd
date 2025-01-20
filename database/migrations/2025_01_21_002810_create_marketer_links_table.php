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
        Schema::create('marketer_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marketer_id')->constrained('marketer_users')->onDelete('cascade');
            $table->foreignId('campaign_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('service_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('provider_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('original_url');
            $table->string('tracking_code', 50)->unique();
            $table->string('shortened_url')->nullable();

            // UTM Parameters
            $table->string('utm_source', 100)->nullable();
            $table->string('utm_medium', 100)->nullable();
            $table->string('utm_campaign', 100)->nullable();
            $table->string('utm_content', 100)->nullable();

            // Statistics
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('unique_clicks')->default(0);
            $table->unsignedInteger('conversions')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->decimal('total_commission', 10, 2)->default(0);

            // Link Status
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('tracking_code');
            $table->index('marketer_id');
            $table->index(['status', 'expires_at']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marketer_links');
    }
};
