<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('department', function (Blueprint $table): void {
            $table->string('dept_code', 20)->nullable()->unique();
            $table->boolean('is_active')->default(true);
        });

        DB::table('department')->orderBy('dept_id')->get()->each(function (object $department): void {
            $base = Str::upper(Str::substr(Str::slug($department->dept_name, ''), 0, 16)) ?: 'DEPT';
            $code = $base;
            $suffix = 1;
            while (DB::table('department')->where('dept_code', $code)->exists()) {
                $code = Str::substr($base, 0, 16).$suffix++;
            }
            DB::table('department')->where('dept_id', $department->dept_id)->update(['dept_code' => $code]);
        });
    }

    public function down(): void
    {
        Schema::table('department', function (Blueprint $table): void {
            $table->dropUnique(['dept_code']);
            $table->dropColumn(['dept_code', 'is_active']);
        });
    }
};
