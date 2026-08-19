<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class DemoTimetableGenerator
{
    public const ACADEMIC_TERM = 'DEMO 2026/2027 Semester 1';

    private const INSTRUCTOR_BY_COURSE = [
        'AFI101' => 'DEMO Instructor Amani', 'AFI102' => 'DEMO Instructor Baraka', 'AFI103' => 'DEMO Instructor Cheusi', 'AFI104' => 'DEMO Instructor Amani',
        'AED101' => 'DEMO Instructor Rehema', 'AED102' => 'DEMO Instructor Safiya', 'AED103' => 'DEMO Instructor Rehema', 'AED104' => 'DEMO Instructor Safiya',
        'BBM101' => 'DEMO Instructor Baraka', 'BBM102' => 'DEMO Instructor Cheusi', 'BBM103' => 'DEMO Instructor Dalili', 'BBM104' => 'DEMO Instructor Kamaria',
        'BBA101' => 'DEMO Instructor Dalili', 'BBA102' => 'DEMO Instructor Eshe', 'BBA103' => 'DEMO Instructor Faraji', 'BBA104' => 'DEMO Instructor Eshe',
        'BCS101' => 'DEMO Instructor Gadi', 'BCS102' => 'DEMO Instructor Hasina', 'BCS103' => 'DEMO Instructor Kamaria', 'BCS104' => 'DEMO Instructor Imara',
        'EHI101' => 'DEMO Instructor Latifa', 'EHI102' => 'DEMO Instructor Mosi', 'EHI103' => 'DEMO Instructor Imara', 'EHI104' => 'DEMO Instructor Latifa',
        'LAW101' => 'DEMO Instructor Nuru', 'LAW102' => 'DEMO Instructor Omari', 'LAW103' => 'DEMO Instructor Pendo', 'LAW104' => 'DEMO Instructor Nuru',
        'SEN101' => 'DEMO Instructor Gadi', 'SEN102' => 'DEMO Instructor Jabali', 'SEN103' => 'DEMO Instructor Hasina', 'SEN104' => 'DEMO Instructor Jabali',
        'EDM101' => 'DEMO Instructor Kamaria', 'EDM102' => 'DEMO Instructor Imara', 'EDM103' => 'DEMO Instructor Mosi', 'EDM104' => 'DEMO Instructor Rehema',
    ];

    /** @return array{requested:int,scheduled:int,unscheduled:array<int,string>,milliseconds:float} */
    public function generate(): array
    {
        $started = hrtime(true);
        $courses = DB::table('courses')->join('programs', 'courses.program_id', '=', 'programs.program_id')
            ->join('student_groups', 'programs.program_id', '=', 'student_groups.program_id')
            ->where('student_groups.group_name', 'like', 'DEMO % Cohort')
            ->select('courses.*', 'student_groups.group_id', 'student_groups.student_count')
            ->orderByDesc('student_groups.student_count')->orderBy('courses.course_code')->get();
        $slots = DB::table('time_slots')->orderByRaw("CASE day_of_week WHEN 'Monday' THEN 1 WHEN 'Tuesday' THEN 2 WHEN 'Wednesday' THEN 3 WHEN 'Thursday' THEN 4 WHEN 'Friday' THEN 5 ELSE 6 END")
            ->orderBy('start_time')->get();
        $venues = DB::table('venues')->orderBy('capacity')->get();
        $instructors = DB::table('instructors')->pluck('instr_id', 'instr_name');
        $availability = DB::table('instructor_availabilities')->where('is_available', true)->get()
            ->mapWithKeys(fn (object $row): array => ["{$row->instr_id}:{$row->slot_id}" => true]);
        DB::table('timetable_entries')->where('academic_term', self::ACADEMIC_TERM)->delete();
        $requested = 0;
        $unscheduled = [];
        foreach ($courses as $course) {
            $sessions = (int) ceil($course->hours_per_week / 2);
            $requested += $sessions;
            $name = self::INSTRUCTOR_BY_COURSE[$course->course_code] ?? null;
            $instructorId = $name ? $instructors->get($name) : null;
            if (! $instructorId) {
                throw new RuntimeException("No demo instructor assignment for {$course->course_code}.");
            }
            for ($session = 1; $session <= $sessions; $session++) {
                if (! $this->schedule($course, (int) $instructorId, $slots, $venues, $availability)) {
                    $unscheduled[] = "{$course->course_code} session {$session}: no valid slot and venue";
                }
            }
        }

        return ['requested' => $requested, 'scheduled' => $requested - count($unscheduled), 'unscheduled' => $unscheduled,
            'milliseconds' => round((hrtime(true) - $started) / 1_000_000, 2)];
    }

    private function schedule(object $course, int $instructorId, $slots, $venues, $availability): bool
    {
        foreach ($slots as $slot) {
            if (! $availability->has("{$instructorId}:{$slot->slot_id}")) {
                continue;
            }
            $busy = DB::table('timetable_entries')->where('academic_term', self::ACADEMIC_TERM)->where('slot_id', $slot->slot_id);
            if ((clone $busy)->where('instr_id', $instructorId)->exists() || (clone $busy)->where('group_id', $course->group_id)->exists()
                || (clone $busy)->where('course_code', $course->course_code)->exists()) {
                continue;
            }
            foreach ($venues as $venue) {
                if ($venue->capacity < $course->student_count || (clone $busy)->where('room_id', $venue->room_id)->exists()) {
                    continue;
                }
                DB::table('timetable_entries')->insert(['course_code' => $course->course_code, 'instr_id' => $instructorId,
                    'room_id' => $venue->room_id, 'group_id' => $course->group_id, 'slot_id' => $slot->slot_id, 'academic_term' => self::ACADEMIC_TERM]);

                return true;
            }
        }

        return false;
    }
}
