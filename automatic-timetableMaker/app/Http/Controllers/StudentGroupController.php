<?php

namespace App\Http\Controllers;

use App\Models\StudentGroup;
use Illuminate\Http\Request;

class StudentGroupController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'group_name' => 'required|string|max:100',
            'student_count' => 'required|integer|min:1',
            'program_id' => 'required|exists:programs,program_id',
        ]);

        StudentGroup::create($validated);

        return redirect()->back()->with('success', 'Student group successfully created!');
    }
}
