<?php

namespace Database\Factories;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Department>
 */
class DepartmentFactory extends Factory
{
    protected $model = Department::class;

    public function definition(): array
    {
        static $index = 0;
        $departments = [
            'Computer Science',
            'Electrical Engineering',
            'Business Administration',
            'Civil Engineering',
            'Mechanical Engineering',
        ];

        $deptName = $departments[$index % count($departments)];
        $index++;

        return [
            'dept_id' => 1000 + ($index - 1),
            'dept_name' => $deptName,
        ];
    }
}
