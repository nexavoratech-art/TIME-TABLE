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
        Schema::create('instructor_availabilities', function (Blueprint $table) {
            $table->id('avail_id')->primary();

            $table->unsignedBigInteger('instr_id');
            $table->unsignedBigInteger('slot_id');

            $table->boolean('is_available')->default(true);

            $table->foreign('instr_id')
                ->references('instr_id')
                ->on('instructors')
                ->onDelete('cascade');

            $table->foreign('slot_id')
                ->references('slot_id')
                ->on('time_slots')
                ->onDelete('cascade');

            $table->unique(
                ['instr_id', 'slot_id'],
                'uq_instr_slot'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_availabilities');
    }
};
