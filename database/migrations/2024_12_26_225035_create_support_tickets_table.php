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
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number', 20);
            $table->foreignId('category_id')->constrained('ticket_categories');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('assigned_to')->nullable()->constrained('users');
            $table->string('subject', 255);
            $table->text('description');
            $table->enum('status', ['new', 'assigned', 'in_progress', 'on_hold', 'resolved', 'closed'])->default('new');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');
            $table->timestamp('due_date')->nullable();
            $table->text('resolution')->nullable();
            $table->timestamps();
            $table->unique('ticket_number');
            $table->index('status');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('support_tickets');
    }
};
