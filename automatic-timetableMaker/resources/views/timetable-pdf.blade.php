<!DOCTYPE html>
<html lang="en"><head><meta charset="UTF-8"><style>
body{font-family:DejaVu Sans,sans-serif;font-size:9px;color:#111}h1,p{text-align:center;margin:4px}table{width:100%;border-collapse:collapse;margin-top:14px}th,td{border:1px solid #777;padding:5px;text-align:left}th{background:#eee}.warning{font-weight:bold;color:#8a1c1c}
</style></head><body>
<h1>University Timetable Demonstration</h1>
<p class="warning">DEMONSTRATION DATA — NOT AN OFFICIAL RUCU TIMETABLE</p>
<p>{{ $department ? $department->dept_code.' — '.$department->dept_name : 'All Departments' }} · {{ \App\Services\DemoTimetableGenerator::ACADEMIC_TERM }}</p>
<table><thead><tr><th>Day</th><th>Period</th><th>Course</th><th>Programme</th><th>Department</th><th>Instructor</th><th>Venue</th><th>Cohort</th></tr></thead><tbody>
@forelse($entries as $entry)<tr><td>{{ $entry->day_of_week }}</td><td>{{ substr($entry->start_time,0,5) }}–{{ substr($entry->end_time,0,5) }}</td><td>{{ $entry->course_code }} — {{ $entry->course_name }}</td><td>{{ $entry->program_name }}</td><td>{{ $entry->dept_name }}</td><td>{{ $entry->instr_name }}</td><td>{{ $entry->room_name }}</td><td>{{ $entry->group_name }}</td></tr>
@empty<tr><td colspan="8">No timetable entries match these filters.</td></tr>@endforelse
</tbody></table></body></html>
