<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'course_code' => 'required|string|max:20|unique:courses,course_code',
            'course_name' => 'required|string|max:150',
            'hours_per_week' => 'required|integer|min:1',
            'program_id' => 'required|exists:programs,program_id',
        ]);

        Course::create($validated);

        return redirect()->back()->with('success', 'Course successfully created!');
    }
}
