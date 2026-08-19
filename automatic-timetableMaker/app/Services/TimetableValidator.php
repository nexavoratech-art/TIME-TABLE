<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class TimetableValidator
{
    /** @return array<string, int> */
    public function validate(string $term): array
    {
        $duplicates = fn (string $column): int => DB::table('timetable_entries')->where('academic_term', $term)
            ->select($column, 'slot_id')->groupBy($column, 'slot_id')->havingRaw('COUNT(*) > 1')->get()->count();

        return [
            'instructor_collisions' => $duplicates('instr_id'), 'venue_collisions' => $duplicates('room_id'), 'cohort_collisions' => $duplicates('group_id'),
            'availability_violations' => DB::table('timetable_entries as e')->leftJoin('instructor_availabilities as a', function ($join): void {
                $join->on('e.instr_id', '=', 'a.instr_id')->on('e.slot_id', '=', 'a.slot_id');
            })->where('e.academic_term', $term)->where(fn ($q) => $q->whereNull('a.avail_id')->orWhere('a.is_available', false))->count(),
            'capacity_violations' => DB::table('timetable_entries as e')->join('venues as v', 'e.room_id', '=', 'v.room_id')
                ->join('student_groups as g', 'e.group_id', '=', 'g.group_id')->where('e.academic_term', $term)->whereColumn('v.capacity', '<', 'g.student_count')->count(),
        ];
    }
}
