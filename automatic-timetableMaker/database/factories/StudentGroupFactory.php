<?php

namespace Database\Factories;

use App\Models\Program;
use App\Models\StudentGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentGroup>
 */
class StudentGroupFactory extends Factory
{
    protected $model = StudentGroup::class;

    public function definition(): array
    {
        static $index = 0;
        $groupNames = [
            'CS-101', 'CS-102', 'CS-103', 'CS-104',
            'SE-201', 'SE-202', 'SE-203', 'SE-204',
            'EE-301', 'EE-302', 'BB-401', 'BB-402',
        ];

        $groupName = $groupNames[$index % count($groupNames)];
        $index++;

        return [
            'group_name' => $groupName,
            'student_count' => 25 + (($index - 1) % 5) * 10,
            'program_id' => Program::query()->inRandomOrder()->value('program_id') ?? 1,
        ];
    }
}
