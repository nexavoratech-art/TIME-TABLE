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
        Schema::create('timetable_entries', function (Blueprint $table) {
            $table->id('entry_id');

            $table->string('course_code', 20);
            $table->unsignedBigInteger('instr_id');
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('slot_id');
            $table->string('academic_term', 50);

            /*
             * Foreign Keys
             */

            $table->foreign('course_code')
                ->references('course_code')
                ->on('courses')
                ->onDelete('cascade');

            $table->foreign('instr_id')
                ->references('instr_id')
                ->on('instructors')
                ->onDelete('cascade');

            $table->foreign('room_id')
                ->references('room_id')
                ->on('venues')
                ->onDelete('cascade');

            $table->foreign('group_id')
                ->references('group_id')
                ->on('student_groups')
                ->onDelete('cascade');

            $table->foreign('slot_id')
                ->references('slot_id')
                ->on('time_slots')
                ->onDelete('cascade');

            /*
             * Genetic Algorithm hard constraints
             */

            // An instructor cannot teach two classes
            // during the same time slot and academic term.
            $table->unique(
                ['instr_id', 'slot_id', 'academic_term'],
                'uq_no_instructor_clash'
            );

            // A room cannot host two classes
            // during the same time slot and academic term.
            $table->unique(
                ['room_id', 'slot_id', 'academic_term'],
                'uq_no_room_clash'
            );

            // A student group cannot attend two classes
            // during the same time slot and academic term.
            $table->unique(
                ['group_id', 'slot_id', 'academic_term'],
                'uq_no_group_clash'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timetable_entries');
    }
};
