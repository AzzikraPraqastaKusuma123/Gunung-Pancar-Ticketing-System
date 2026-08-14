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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->enum('category', ['dewasa', 'anak-anak', 'group']);
            $table->integer('participant_count')->default(1);
            $table->string('qr_code_path')->nullable();
            $table->enum('status', ['booked', 'printed', 'used', 'cancelled'])->default('booked');
            $table->foreignId('scanned_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
