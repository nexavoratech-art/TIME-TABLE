<?php

namespace Database\Factories;

use App\Models\Department;
use App\Models\Instructor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Instructor>
 */
class InstructorFactory extends Factory
{
    protected $model = Instructor::class;

    public function definition(): array
    {
        static $index = 0;
        $instructors = [
            'Dr. Sarah Johnson',
            'Prof. Daniel Clark',
            'Dr. Aisha Khan',
            'Mr. Samuel Lee',
            'Dr. Nia Patel',
            'Prof. Omar Hassan',
            'Dr. Chloe Martin',
            'Mr. Ethan Brooks',
        ];

        $instructorName = $instructors[$index % count($instructors)];
        $index++;

        return [
            'instr_name' => $instructorName,
            'dept_id' => Department::query()->inRandomOrder()->value('dept_id') ?? 1000,
        ];
    }
}
