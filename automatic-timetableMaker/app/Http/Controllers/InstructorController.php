<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'instr_name' => 'required|string|max:150',
            'dept_id' => ['required', Rule::exists('department', 'dept_id')->where('is_active', true)],
        ]);

        Instructor::create($validated);

        return redirect()->back()->with('success', 'Instructor successfully created!');
    }
}
