<?php

namespace Tests\Feature;

use Database\Seeders\DemoTimetableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DemoTimetableSeederTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DemoTimetableSeeder::class);
    }

    public function test_it_seeds_the_complete_rucu_demonstration_dataset(): void
    {
        $this->assertSame(6, DB::table('department')->count());
        $this->assertSame(6, DB::table('department')->whereNotNull('dept_code')->where('is_active', true)->count());
        $this->assertSame(9, DB::table('programs')->count());
        $this->assertSame(9, DB::table('student_groups')->count());
        $this->assertSame(18, DB::table('instructors')->count());
        $this->assertSame(36, DB::table('courses')->count());
        $this->assertSame(10, DB::table('venues')->count());
        $this->assertSame(20, DB::table('time_slots')->count());
        $this->assertSame(360, DB::table('instructor_availabilities')->count());
        $this->assertSame(19, DB::table('instructor_availabilities')->where('is_available', false)->count());
    }

    public function test_all_nine_named_programmes_and_their_courses_are_present(): void
    {
        $names = [
            'Bachelor of Accounting and Finance with IT', 'Bachelor of Arts with Education',
            'Bachelor of Banking and Microfinance', 'Bachelor of Business Administration',
            'Bachelor of Computer Science',
            'Bachelor of Environmental Health Sciences with Information Technology',
            'Bachelor of Law', 'Bachelor of Science in Software Engineering',
            'Bachelor of Science with Education (IT & Mathematics)',
        ];

        $this->assertSame(9, DB::table('programs')->whereIn('program_name', $names)->count());
        $this->assertSame(0, DB::table('programs')
            ->leftJoin('courses', 'programs.program_id', '=', 'courses.program_id')
            ->whereIn('programs.program_name', $names)
            ->groupBy('programs.program_id')
            ->havingRaw('COUNT(courses.course_code) <> 4')
            ->count());
    }

    public function test_relationships_and_scheduling_inputs_are_valid(): void
    {
        $checks = [
            ['programs as child', 'department as parent', 'child.dept_id', 'parent.dept_id'],
            ['student_groups as child', 'programs as parent', 'child.program_id', 'parent.program_id'],
            ['instructors as child', 'department as parent', 'child.dept_id', 'parent.dept_id'],
            ['courses as child', 'programs as parent', 'child.program_id', 'parent.program_id'],
            ['instructor_availabilities as child', 'instructors as parent', 'child.instr_id', 'parent.instr_id'],
            ['instructor_availabilities as child', 'time_slots as parent', 'child.slot_id', 'parent.slot_id'],
        ];
        foreach ($checks as [$child, $parent, $childKey, $parentKey]) {
            $this->assertSame(0, DB::table(DB::raw($child))->leftJoin(DB::raw($parent), $childKey, '=', $parentKey)
                ->whereNull($parentKey)->count());
        }

        $this->assertSame(0, DB::table('venues')->where('capacity', '<', 1)->count());
        $this->assertSame(0, DB::table('student_groups')->where('student_count', '<', 1)->count());
        $this->assertSame(0, DB::table('courses')->where('hours_per_week', '<', 1)->count());
        $this->assertSame(20, DB::table('time_slots')->select('day_of_week', 'start_time', 'end_time')->distinct()->count());
        $this->assertSame(360, DB::table('instructor_availabilities')->select('instr_id', 'slot_id')->distinct()->count());
    }

    public function test_the_demonstration_seeder_is_safely_re_runnable(): void
    {
        $this->seed(DemoTimetableSeeder::class);

        $this->assertSame(9, DB::table('programs')->count());
        $this->assertSame(36, DB::table('courses')->count());
        $this->assertSame(360, DB::table('instructor_availabilities')->count());
    }
}
