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
        Schema::create('courses', function (Blueprint $table) {
            $table->string('course_code', 20)->primary();
            $table->string('course_name', 150);
            $table->unsignedInteger('hours_per_week');
            $table->unsignedBigInteger('program_id');
            // $table->id('group_id');


            $table->foreign('program_id')
                ->references('program_id')
                ->on('programs')
                ->onDelete('cascade');

            // $table->foreign('group_id')
            //     ->references('group_id')
            //     ->on('student_groups')
            //     ->onDelete('cascade');

            // $table->check('hours_per_week > 0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
