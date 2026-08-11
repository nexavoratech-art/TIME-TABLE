<?php

namespace App\Http\Controllers;

use App\Models\Instructor;
use Illuminate\Http\Request;

class InstructorController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'instr_name' => 'required|string|max:150',
            'dept_id' => 'required|exists:department,dept_id',
        ]);

        Instructor::create($validated);

        return redirect()->back()->with('success', 'Instructor successfully created!');
    }
}
