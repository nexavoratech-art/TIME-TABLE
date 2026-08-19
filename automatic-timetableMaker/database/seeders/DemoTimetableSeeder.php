<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoTimetableSeeder extends Seeder
{
    private const DEPARTMENT_CODES = [
        'Accounting and Finance' => 'AF',
        'DEMO Education Studies' => 'DEMO-EDU',
        'Management Sciences and Procurement' => 'MSP',
        'Computer Science' => 'CS',
        'Mathematics and Natural Sciences' => 'MNS',
        'Public Law' => 'PL',
    ];

    private const PROGRAMMES = [
        'BAFIT' => ['Bachelor of Accounting and Finance with IT', 'Accounting and Finance', 85],
        'BAED' => ['Bachelor of Arts with Education', 'DEMO Education Studies', 120],
        'BBM' => ['Bachelor of Banking and Microfinance', 'Accounting and Finance', 70],
        'BBA' => ['Bachelor of Business Administration', 'Management Sciences and Procurement', 110],
        'BCS' => ['Bachelor of Computer Science', 'Computer Science', 90],
        'BEHSIT' => ['Bachelor of Environmental Health Sciences with Information Technology', 'Mathematics and Natural Sciences', 55],
        'LLB' => ['Bachelor of Law', 'Public Law', 130],
        'BSCSE' => ['Bachelor of Science in Software Engineering', 'Computer Science', 75],
        'BSCED-ITM' => ['Bachelor of Science with Education (IT & Mathematics)', 'Mathematics and Natural Sciences', 60],
    ];

    public function run(): void
    {
        DB::transaction(function (): void {
            $departments = $this->seedDepartments();
            $programmes = $this->seedProgrammes($departments);
            $this->seedCohorts($programmes);
            $instructors = $this->seedInstructors($departments);
            $this->seedCourses($programmes);
            $this->seedVenues();
            $slots = $this->seedTimeSlots();
            $this->seedAvailability($instructors, $slots);
        });
    }

    /** @return array<string, int> */
    private function seedDepartments(): array
    {
        $names = array_values(array_unique(array_column(self::PROGRAMMES, 1)));
        $ids = [];

        foreach ($names as $name) {
            $id = DB::table('department')->where('dept_name', $name)->value('dept_id');
            if ($id === null) {
                $id = ((int) DB::table('department')->max('dept_id')) + 1;
                DB::table('department')->insert(['dept_id' => $id, 'dept_code' => self::DEPARTMENT_CODES[$name], 'dept_name' => $name, 'is_active' => true]);
            } else {
                DB::table('department')->where('dept_id', $id)->update([
                    'dept_code' => self::DEPARTMENT_CODES[$name], 'is_active' => true,
                ]);
            }
            $ids[$name] = (int) $id;
        }

        return $ids;
    }

    /** @param array<string, int> $departments
     * @return array<string, int>
     */
    private function seedProgrammes(array $departments): array
    {
        $ids = [];
        foreach (self::PROGRAMMES as $code => [$name, $department]) {
            $record = DB::table('programs')->where('program_name', $name)->first();
            if ($record === null) {
                $id = DB::table('programs')->insertGetId([
                    'program_name' => $name,
                    'dept_id' => $departments[$department],
                ], 'program_id');
            } else {
                $id = $record->program_id;
                DB::table('programs')->where('program_id', $id)->update(['dept_id' => $departments[$department]]);
            }
            $ids[$code] = (int) $id;
        }

        return $ids;
    }

    /** @param array<string, int> $programmes */
    private function seedCohorts(array $programmes): void
    {
        foreach (self::PROGRAMMES as $code => [, , $size]) {
            $name = "DEMO {$code} Year 1 Cohort";
            DB::table('student_groups')->updateOrInsert(
                ['group_name' => $name],
                ['student_count' => $size, 'program_id' => $programmes[$code]]
            );
        }
    }

    /** @param array<string, int> $departments
     * @return array<string, int>
     */
    private function seedInstructors(array $departments): array
    {
        $rows = [
            ['DEMO Instructor Amani', 'Accounting and Finance'], ['DEMO Instructor Baraka', 'Accounting and Finance'],
            ['DEMO Instructor Cheusi', 'Accounting and Finance'], ['DEMO Instructor Dalili', 'Management Sciences and Procurement'],
            ['DEMO Instructor Eshe', 'Management Sciences and Procurement'], ['DEMO Instructor Faraji', 'Management Sciences and Procurement'],
            ['DEMO Instructor Gadi', 'Computer Science'], ['DEMO Instructor Hasina', 'Computer Science'],
            ['DEMO Instructor Imara', 'Computer Science'], ['DEMO Instructor Jabali', 'Computer Science'],
            ['DEMO Instructor Kamaria', 'Mathematics and Natural Sciences'], ['DEMO Instructor Latifa', 'Mathematics and Natural Sciences'],
            ['DEMO Instructor Mosi', 'Mathematics and Natural Sciences'], ['DEMO Instructor Nuru', 'Public Law'],
            ['DEMO Instructor Omari', 'Public Law'], ['DEMO Instructor Pendo', 'Public Law'],
            ['DEMO Instructor Rehema', 'DEMO Education Studies'], ['DEMO Instructor Safiya', 'DEMO Education Studies'],
        ];
        $ids = [];
        foreach ($rows as [$name, $department]) {
            $record = DB::table('instructors')->where('instr_name', $name)->first();
            if ($record === null) {
                $id = DB::table('instructors')->insertGetId([
                    'instr_name' => $name, 'dept_id' => $departments[$department],
                ], 'instr_id');
            } else {
                $id = $record->instr_id;
                DB::table('instructors')->where('instr_id', $id)->update(['dept_id' => $departments[$department]]);
            }
            $ids[$name] = (int) $id;
        }

        return $ids;
    }

    /** @param array<string, int> $programmes */
    private function seedCourses(array $programmes): void
    {
        $courses = [
            'BAFIT' => [['AFI101', 'Principles of Accounting', 4], ['AFI102', 'Business Mathematics', 4], ['AFI103', 'Financial Information Systems', 3], ['AFI104', 'Fundamentals of Finance', 4]],
            'BAED' => [['AED101', 'Foundations of Education', 4], ['AED102', 'Educational Psychology', 3], ['AED103', 'Communication Skills', 2], ['AED104', 'Teaching Methods', 4]],
            'BBM' => [['BBM101', 'Principles of Banking', 4], ['BBM102', 'Microfinance Operations', 4], ['BBM103', 'Business Economics', 3], ['BBM104', 'Financial Mathematics', 4]],
            'BBA' => [['BBA101', 'Principles of Management', 4], ['BBA102', 'Business Communication', 2], ['BBA103', 'Principles of Marketing', 4], ['BBA104', 'Business Statistics', 3]],
            'BCS' => [['BCS101', 'Introduction to Programming', 4], ['BCS102', 'Computer Systems', 3], ['BCS103', 'Discrete Mathematics', 4], ['BCS104', 'Database Fundamentals', 4]],
            'BEHSIT' => [['EHI101', 'Introduction to Environmental Health', 4], ['EHI102', 'Environmental Microbiology', 4], ['EHI103', 'Health Information Systems', 3], ['EHI104', 'Environmental Data Management', 4]],
            'LLB' => [['LAW101', 'Introduction to Legal Systems', 4], ['LAW102', 'Constitutional Law', 4], ['LAW103', 'Law of Contract', 4], ['LAW104', 'Legal Research and Writing', 3]],
            'BSCSE' => [['SEN101', 'Programming Fundamentals', 4], ['SEN102', 'Software Engineering Principles', 3], ['SEN103', 'Data Structures and Algorithms', 4], ['SEN104', 'Requirements Engineering', 4]],
            'BSCED-ITM' => [['EDM101', 'Calculus I', 4], ['EDM102', 'Foundations of Computer Science', 4], ['EDM103', 'Mathematics Teaching Methods', 3], ['EDM104', 'Educational Technology', 4]],
        ];

        foreach ($courses as $programmeCode => $programmeCourses) {
            foreach ($programmeCourses as [$code, $name, $hours]) {
                DB::table('courses')->updateOrInsert(['course_code' => $code], [
                    'course_name' => $name,
                    'hours_per_week' => $hours,
                    'program_id' => $programmes[$programmeCode],
                ]);
            }
        }
    }

    private function seedVenues(): void
    {
        $venues = [
            ['DEMO Lecture Hall A', 200, 'Lecture Hall'], ['DEMO Lecture Hall B', 150, 'Lecture Hall'],
            ['DEMO Lecture Hall C', 120, 'Lecture Hall'], ['DEMO Multipurpose Hall', 100, 'Lecture Hall'],
            ['DEMO Computer Lab 1', 80, 'Computer Lab'], ['DEMO Computer Lab 2', 60, 'Computer Lab'],
            ['DEMO Science Lab', 60, 'Science Lab'], ['DEMO Law Room', 150, 'Classroom'],
            ['DEMO Seminar Room 1', 50, 'Seminar Room'], ['DEMO Seminar Room 2', 40, 'Seminar Room'],
        ];
        foreach ($venues as [$name, $capacity, $type]) {
            DB::table('venues')->updateOrInsert(['room_name' => $name], [
                'capacity' => $capacity, 'room_type' => $type,
            ]);
        }
    }

    /** @return array<int, object> */
    private function seedTimeSlots(): array
    {
        $periods = [['08:00:00', '10:00:00'], ['10:15:00', '12:15:00'],
            ['13:30:00', '15:30:00'], ['15:45:00', '17:45:00']];
        foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'] as $day) {
            foreach ($periods as [$start, $end]) {
                DB::table('time_slots')->insertOrIgnore([
                    'day_of_week' => $day, 'start_time' => $start, 'end_time' => $end,
                ]);
            }
        }

        $dayOrder = array_flip(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']);

        return DB::table('time_slots')->whereIn('day_of_week', array_keys($dayOrder))->get()
            ->filter(fn (object $slot) => in_array(substr($slot->start_time, 0, 8), array_column($periods, 0), true))
            ->sortBy(fn (object $slot) => sprintf('%d-%s', $dayOrder[$slot->day_of_week], $slot->start_time))
            ->values()->all();
    }

    /** @param array<string, int> $instructors
     * @param  array<int, object>  $slots
     */
    private function seedAvailability(array $instructors, array $slots): void
    {
        $restrictions = [
            'DEMO Instructor Amani' => [1, 2],
            'DEMO Instructor Baraka' => [11, 12],
            'DEMO Instructor Gadi' => [5, 6, 7, 8, 13, 14, 15, 16],
            'DEMO Instructor Hasina' => [17, 18, 19, 20],
            'DEMO Instructor Kamaria' => [5],
            'DEMO Instructor Nuru' => [9, 10],
        ];
        $rows = [];
        foreach ($instructors as $name => $instructorId) {
            foreach ($slots as $index => $slot) {
                $rows[] = [
                    'instr_id' => $instructorId,
                    'slot_id' => $slot->slot_id,
                    'is_available' => ! in_array($index + 1, $restrictions[$name] ?? [], true),
                ];
            }
        }
        DB::table('instructor_availabilities')->upsert($rows, ['instr_id', 'slot_id'], ['is_available']);
    }
}
