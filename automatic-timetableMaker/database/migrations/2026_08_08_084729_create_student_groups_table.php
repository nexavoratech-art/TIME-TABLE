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
        Schema::create('student_groups', function (Blueprint $table) {
            $table->id('group_id')->primary();
            $table->string('group_name', 100);
            $table->unsignedInteger('student_count');
            $table->unsignedBigInteger('program_id');

            $table->foreign('program_id')
                ->references('program_id')
                ->on('programs')
                ->onDelete('cascade');

            // $table->check('student_count > 0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_groups');
    }
};
