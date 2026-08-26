<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'course_code' => ['required', 'string', 'max:20', 'unique:courses,course_code'],
            'course_name' => ['required', 'string', 'max:150'],
            'hours_per_week' => ['required', 'integer', 'min:1', 'max:20'],
            'program_id' => ['required', 'exists:programs,program_id'],
            'instr_id' => ['required', 'exists:instructors,instr_id'],
            'year_of_study' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        Course::query()->create($validated);

        return back()->with('success', 'Course and teaching assignment created successfully.');
    }

    public function updateAssignment(Request $request, Course $course): RedirectResponse
    {
        $validated = $request->validate([
            'instr_id' => ['required', 'exists:instructors,instr_id'],
            'year_of_study' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        $course->update($validated);

        return back()->with('success', 'Course teaching assignment updated successfully.');
    }
}
