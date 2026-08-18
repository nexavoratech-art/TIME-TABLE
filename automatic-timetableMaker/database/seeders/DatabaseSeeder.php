<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Department;
use App\Models\Instructor;
use App\Models\InstructorAvailability;
use App\Models\Program;
use App\Models\StudentGroup;
use App\Models\TimeSlot;
use App\Models\User;
use App\Models\Venue;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $departments = Department::factory()->count(5)->create();

        $programs = collect();
        foreach ($departments as $department) {
            $programs = $programs->merge(
                Program::factory()->count(2)->create([
                    'dept_id' => $department->dept_id,
                ])
            );
        }

        StudentGroup::factory()->count(12)->create([
            'program_id' => fn () => $programs->random()->program_id,
        ]);

        $instructors = Instructor::factory()->count(8)->create([
            'dept_id' => fn () => $departments->random()->dept_id,
        ]);

        Course::factory()->count(12)->create([
            'program_id' => fn () => $programs->random()->program_id,
        ]);

        Venue::factory()->count(6)->create();

        $timeSlots = TimeSlot::factory()->count(20)->create();

        foreach ($instructors as $instructor) {
            foreach ($timeSlots as $slot) {
                InstructorAvailability::factory()->create([
                    'instr_id' => $instructor->instr_id,
                    'slot_id' => $slot->slot_id,
                ]);
            }
        }
    }
}
