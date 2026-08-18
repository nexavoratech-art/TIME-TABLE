<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Department;
use App\Models\Instructor;
use App\Models\InstructorAvailability;
use App\Models\Program;
use App\Models\StudentGroup;
use App\Models\TimeSlot;
use App\Models\Venue;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TimetableSeedDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_seed_data_populates_timetable_entities(): void
    {
        $this->seed(\Database\Seeders\DatabaseSeeder::class);

        $this->assertGreaterThanOrEqual(3, Department::count());
        $this->assertGreaterThanOrEqual(6, Program::count());
        $this->assertGreaterThanOrEqual(12, StudentGroup::count());
        $this->assertGreaterThanOrEqual(8, Instructor::count());
        $this->assertGreaterThanOrEqual(12, Course::count());
        $this->assertGreaterThanOrEqual(6, Venue::count());
        $this->assertGreaterThanOrEqual(20, TimeSlot::count());
        $this->assertGreaterThanOrEqual(8 * 20, InstructorAvailability::count());
    }
}
