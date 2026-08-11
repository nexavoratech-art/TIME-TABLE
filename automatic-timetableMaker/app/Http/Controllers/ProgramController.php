<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;

class ProgramController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dept_id' => 'required|exists:department,dept_id',
            'program_name' => 'required|string|max:150',
        ]);

        Program::create($validated);

        return redirect()->back()->with('success', 'Program successfully created!');
    }
}