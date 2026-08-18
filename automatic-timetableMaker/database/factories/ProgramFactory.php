<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Program>
 */
class ProgramFactory extends Factory
{
    protected $model = Program::class;

    public function definition(): array
    {
        static $index = 0;
        $programs = [
            ['program_name' => 'BSc Computer Science', 'dept_id' => 1000],
            ['program_name' => 'BSc Software Engineering', 'dept_id' => 1000],
            ['program_name' => 'BEng Electrical Engineering', 'dept_id' => 1001],
            ['program_name' => 'BEng Electronics', 'dept_id' => 1001],
            ['program_name' => 'BBA Marketing', 'dept_id' => 1002],
            ['program_name' => 'BBA Finance', 'dept_id' => 1002],
            ['program_name' => 'BSc Civil Engineering', 'dept_id' => 1003],
            ['program_name' => 'BSc Structural Engineering', 'dept_id' => 1003],
        ];

        $program = $programs[$index % count($programs)];
        $index++;

        return [
            'program_name' => $program['program_name'],
            'dept_id' => Department::query()->where('dept_id', $program['dept_id'])->exists()
                ? $program['dept_id']
                : Department::query()->inRandomOrder()->value('dept_id') ?? 1000,
        ];
    }
}
