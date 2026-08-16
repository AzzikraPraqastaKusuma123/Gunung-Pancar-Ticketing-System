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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('customer_segment')->nullable(); // family, friends, corporate, school, outbound
            $table->string('activity_type')->nullable(); // camping, outbound, trekking, etc.
            $table->integer('pax')->nullable();
            $table->date('event_date')->nullable();
            $table->text('needs')->nullable(); // kebutuhan customer
            $table->string('source')->nullable(); // source channel (WA, IG, FB)
            $table->string('pic_sales')->nullable(); // sales in charge
            $table->string('status')->default('New Lead'); // New Lead -> Contacted -> Qualified -> Quotation -> Negotiation -> Booked -> Completed
            $table->dateTime('last_follow_up')->nullable();
            $table->dateTime('next_follow_up')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
