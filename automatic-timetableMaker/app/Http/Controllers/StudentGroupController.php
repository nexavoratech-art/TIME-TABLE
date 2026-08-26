<?php

namespace App\Http\Controllers;

use App\Models\StudentGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StudentGroupController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'group_name' => ['required', 'string', 'max:100'],
            'student_count' => ['required', 'integer', 'min:1'],
            'program_id' => ['required', 'exists:programs,program_id'],
            'year_of_study' => ['required', 'integer', 'min:1', 'max:8'],
        ]);

        StudentGroup::query()->create($validated);

        return back()->with('success', 'Student group created successfully.');
    }
}
