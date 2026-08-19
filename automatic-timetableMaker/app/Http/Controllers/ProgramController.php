<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dept_id' => ['required', Rule::exists('department', 'dept_id')->where('is_active', true)],
            'program_name' => 'required|string|max:150',
        ]);

        Program::create($validated);

        return redirect()->back()->with('success', 'Program successfully created!');
    }
}
