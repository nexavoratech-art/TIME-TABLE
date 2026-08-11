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
        Schema::create('department', function (Blueprint $table) {
        $table->unsignedBigInteger('dept_id')->primary(); // BIGINT UNSIGNED PRIMARY KEY
        $table->string('dept_name', 150); // VARCHAR(150) NOT NULL
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};
