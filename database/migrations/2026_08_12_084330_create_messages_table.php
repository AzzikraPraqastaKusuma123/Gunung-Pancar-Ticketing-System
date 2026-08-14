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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained()->cascadeOnDelete();
            $table->string('sender_type'); // 'customer', 'user', 'ai', 'system'
            $table->unsignedBigInteger('sender_id')->nullable();
            $table->text('content')->nullable();
            $table->string('message_type')->default('text'); // 'text', 'image', 'document', 'audio'
            $table->string('status')->default('sent'); // 'sent', 'delivered', 'read', 'failed'
            $table->string('provider_message_id')->nullable()->index();
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->index(['sender_type', 'sender_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
