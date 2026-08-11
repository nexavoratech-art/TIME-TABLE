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
        Schema::create('venues', function (Blueprint $table) {
            $table->id('room_id')->primary();
            $table->string('room_name', 50);
            $table->unsignedInteger('capacity');
            $table->string('room_type', 50)->default('Lecture Hall');

            $table->index('capacity', 'idx_venue_capacity');

            // $table->check('capacity > 0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venues');
    }
};
