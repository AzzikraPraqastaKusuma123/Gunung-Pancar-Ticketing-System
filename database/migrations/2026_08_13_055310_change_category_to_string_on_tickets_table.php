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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('category')->change();
        });
        Schema::table('booking_items', function (Blueprint $table) {
            $table->string('category')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // SQLite has limited alter table support for enums, so we leave it as string in down
    }
};
