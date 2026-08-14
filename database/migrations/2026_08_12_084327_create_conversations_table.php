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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('whatsapp_account_id')->constrained()->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->string('status')->default('AI_HANDLING'); // AI_HANDLING, HUMAN_HANDLING, WAITING, ESCALATED, CLOSED
            $table->string('priority')->default('normal'); // low, normal, high
            $table->integer('unread_count')->default(0);
            $table->timestamp('last_message_at')->nullable();
            $table->text('ai_summary')->nullable();
            $table->foreignId('assigned_agent_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
