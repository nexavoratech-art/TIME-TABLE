<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Program;
use App\Services\DemoTimetableGenerator;
use App\Services\TimetableValidator;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class TimetableController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $this->validatedFilters($request);
        $entries = $this->entriesQuery($filters)->get();
        $departments = Department::query()->where('is_active', true)->orderBy('dept_name')->get();
        $programs = Program::query()->when($filters['department'] ?? null, fn ($query, $id) => $query->where('dept_id', $id))->orderBy('program_name')->get();

        return view('timetable', compact('entries', 'departments', 'programs', 'filters'));
    }

    public function generate(DemoTimetableGenerator $generator, TimetableValidator $validator): RedirectResponse
    {
        $result = $generator->generate();
        $validation = $validator->validate(DemoTimetableGenerator::ACADEMIC_TERM);

        return redirect()->route('timetable')->with('generation', compact('result', 'validation'));
    }

    public function pdf(Request $request): Response
    {
        $filters = $this->validatedFilters($request);
        $entries = $this->entriesQuery($filters)->get();
        $department = isset($filters['department']) ? Department::query()->find($filters['department']) : null;

        return Pdf::loadView('timetable-pdf', compact('entries', 'filters', 'department'))
            ->setPaper('a4', 'landscape')->download('department-timetable.pdf');
    }

    /** @param array<string, int|string> $filters */
    public function entriesQuery(array $filters): Builder
    {
        return DB::table('timetable_entries as e')->join('courses as c', 'e.course_code', '=', 'c.course_code')
            ->join('programs as p', 'c.program_id', '=', 'p.program_id')->join('department as d', 'p.dept_id', '=', 'd.dept_id')
            ->join('instructors as i', 'e.instr_id', '=', 'i.instr_id')->join('venues as v', 'e.room_id', '=', 'v.room_id')
            ->join('student_groups as g', 'e.group_id', '=', 'g.group_id')->join('time_slots as s', 'e.slot_id', '=', 's.slot_id')
            ->where('e.academic_term', DemoTimetableGenerator::ACADEMIC_TERM)
            ->when($filters['department'] ?? null, fn ($query, $id) => $query->where('d.dept_id', $id))
            ->when($filters['programme'] ?? null, fn ($query, $id) => $query->where('p.program_id', $id))
            ->when($filters['year'] ?? null, fn ($query, $year) => $query->where('g.group_name', 'like', "%Year {$year}%"))
            ->when($filters['semester'] ?? null, fn ($query, $semester) => $query->where('e.academic_term', 'like', "%Semester {$semester}%"))
            ->select('e.*', 'c.course_name', 'i.instr_name', 'v.room_name', 'g.group_name', 's.day_of_week', 's.start_time', 's.end_time',
                'p.program_id', 'p.program_name', 'd.dept_id', 'd.dept_code', 'd.dept_name')->orderBy('s.slot_id')->orderBy('c.course_code');
    }

    /** @return array<string, int|string> */
    private function validatedFilters(Request $request): array
    {
        return array_filter($request->validate([
            'department' => ['nullable', 'integer', 'exists:department,dept_id'],
            'programme' => ['nullable', 'integer', 'exists:programs,program_id'],
            'year' => ['nullable', 'integer', 'between:1,6'],
            'semester' => ['nullable', 'integer', 'between:1,3'],
        ]), fn ($value) => $value !== null && $value !== '');
    }
}
