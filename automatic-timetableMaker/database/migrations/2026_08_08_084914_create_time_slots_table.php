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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id('slot_id')->primary();

            $table->enum('day_of_week', [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday'
            ]);

            $table->time('start_time');
            $table->time('end_time');

            $table->unique(
                ['day_of_week', 'start_time', 'end_time'],
                'uq_time_slot'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
