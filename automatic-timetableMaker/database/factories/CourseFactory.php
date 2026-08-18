<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Program;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        static $index = 0;
        $courses = [
            ['code' => 'CS101', 'name' => 'Intro to Programming', 'hours' => 3],
            ['code' => 'CS102', 'name' => 'Data Structures', 'hours' => 4],
            ['code' => 'CS201', 'name' => 'Algorithms', 'hours' => 3],
            ['code' => 'CS301', 'name' => 'Database Systems', 'hours' => 4],
            ['code' => 'CS302', 'name' => 'Operating Systems', 'hours' => 3],
            ['code' => 'SE101', 'name' => 'Software Engineering I', 'hours' => 3],
            ['code' => 'SE201', 'name' => 'Requirements Engineering', 'hours' => 3],
            ['code' => 'EE101', 'name' => 'Circuit Analysis', 'hours' => 4],
            ['code' => 'EE201', 'name' => 'Signals and Systems', 'hours' => 3],
            ['code' => 'BA101', 'name' => 'Principles of Management', 'hours' => 2],
            ['code' => 'BA201', 'name' => 'Marketing Principles', 'hours' => 3],
            ['code' => 'CE101', 'name' => 'Engineering Materials', 'hours' => 3],
        ];

        $course = $courses[$index % count($courses)];
        $index++;

        return [
            'course_code' => $course['code'],
            'course_name' => $course['name'],
            'hours_per_week' => $course['hours'],
            'program_id' => Program::query()->inRandomOrder()->value('program_id') ?? 1,
        ];
    }
}
