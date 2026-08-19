<?php

namespace Tests\Feature;

use App\Services\DemoTimetableGenerator;
use App\Services\TimetableValidator;
use Database\Seeders\DemoTimetableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DemoTimetableGenerationTest extends TestCase
{
    use RefreshDatabase;

    public function test_generator_schedules_every_required_session_without_constraint_violations(): void
    {
        $this->seed(DemoTimetableSeeder::class);
        $result = app(DemoTimetableGenerator::class)->generate();
        $validation = app(TimetableValidator::class)->validate(DemoTimetableGenerator::ACADEMIC_TERM);

        $this->assertSame(70, $result['requested']);
        $this->assertSame(70, $result['scheduled']);
        $this->assertSame([], $result['unscheduled']);
        $this->assertSame(70, DB::table('timetable_entries')->where('academic_term', DemoTimetableGenerator::ACADEMIC_TERM)->count());
        $this->assertSame([
            'instructor_collisions' => 0, 'venue_collisions' => 0, 'cohort_collisions' => 0,
            'availability_violations' => 0, 'capacity_violations' => 0,
        ], $validation);
    }

    public function test_generation_is_repeatable_and_timetable_page_renders_real_entries(): void
    {
        $this->seed(DemoTimetableSeeder::class);
        $generator = app(DemoTimetableGenerator::class);
        $generator->generate();
        $generator->generate();

        $this->assertSame(70, DB::table('timetable_entries')->count());
        $this->get('/timetable')->assertOk()->assertSee('DEMONSTRATION DATA')->assertSee('AFI101')->assertSee('DEMO Instructor Amani');
    }
}
