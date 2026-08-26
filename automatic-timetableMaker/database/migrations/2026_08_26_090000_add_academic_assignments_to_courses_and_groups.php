<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->unsignedBigInteger('instr_id')->nullable()->after('program_id');
            $table->unsignedTinyInteger('year_of_study')->nullable()->after('instr_id');
            $table->foreign('instr_id')->references('instr_id')->on('instructors')->nullOnDelete();
            $table->index(['program_id', 'year_of_study'], 'idx_course_program_year');
        });

        Schema::table('student_groups', function (Blueprint $table) {
            $table->unsignedTinyInteger('year_of_study')->default(1)->after('program_id');
            $table->index(['program_id', 'year_of_study'], 'idx_group_program_year');
        });
    }

    public function down(): void
    {
        Schema::table('student_groups', function (Blueprint $table) {
            $table->dropIndex('idx_group_program_year');
            $table->dropColumn('year_of_study');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['instr_id']);
            $table->dropIndex('idx_course_program_year');
            $table->dropColumn(['instr_id', 'year_of_study']);
        });
    }
};
